"""CARDIGAN Data Extract Script

This script rearranges raw CARDIGAN data from csv files into
csv files that can be imported directly into the database.
"""

import csv
from datetime import datetime


# noinspection PyUnusedLocal
def convertTsToSQL(ts, a):
    uts = datetime.strptime(ts, "%d/%m/%Y %H:%M")
    return datetime.strftime(uts, "%Y-%m-%d %H:%M:%S")


# noinspection PyUnusedLocal
def convertDateToSQL(date, a):
    uts = datetime.strptime(date, "%d/%m/%Y")
    return datetime.strftime(uts, "%Y-%m-%d")


def readCSVFile(fileName):
    """Reads a CSV file and represents the data as a dictionary
    :param fileName: The name of the csv file, used for the open() function
    :type fileName: str
    :returns: a dictionary, where each key represents a column title and
        each value represents an array of values of the corresponding column
    :rtype: dict
    """
    allData = {}

    with open(fileName) as csvDataFile:
        csvReader = csv.reader(csvDataFile)
        titles = next(csvReader)
        for t in titles:
            allData[t] = []

        for row in csvReader:
            i = 0
            for datum in row:
                allData[titles[i]].append(datum)
                i += 1

    return allData


def formatBoolean(dataStr, attr):
    """If the datum is represented by "yes/no", then convert
     to a boolean representation, except for attributes 64 and 67,
     (musculoskeletal injury) where it must be manipulated.
    """
    if dataStr in ['Yes', 'YES', 'Yes.', 'Si', 'SÍ', 'yes']:
        return 1
    elif dataStr in ['No', 'NO', 'no']:
        return 0
    else:
        # If Entity == musculoskeletal injury, the
        # attributes are an exception to the rule
        if attr == 67:
            varcharList = dataStr.split(' ')
            varcharList = [
                item for item in varcharList
                if item not in ['Yes', 'Yes,', 'and']
            ]
            return varcharList
        if attr == 64:
            return [dataStr.replace('Yes, ', '')]
        return dataStr


# noinspection PyUnusedLocal
def formatInteger(dataStr, a):
    if dataStr in ['Never', 'Strongly disagree', 'Strongly Disagree']:
        return 0
    elif dataStr in ['One day', 'Disagree', 'I']:
        return 1
    elif dataStr in ['Both days', 'Two days', 'Slightly disagree',
                     'Slightly Disagree', 'II']:
        return 2
    elif dataStr in ['Three days', 'Neither agree nor disagree',
                     'III', 'Neutral']:
        return 3
    elif dataStr in ['Four days', 'Slightly agree', 'Slightly Agree', 'IV']:
        return 4
    elif dataStr in ['Five days', 'Agree', 'V']:
        return 5
    elif dataStr in ['Strongly agree', 'Strongly Agree', 'VI']:
        return 6
    else:
        return dataStr


def reformatValueFromType(dataType, value, attr):
    """A switch statement to reformat values into SQL-friendly data types.
    """
    formatsPerType = {
        'DATE':         convertDateToSQL,
        'TIMESTAMP':    convertTsToSQL,
        'BOOL':         formatBoolean,
        'INT':          formatInteger
    }
    for f in formatsPerType:
        if dataType == f:
            return formatsPerType[f](value, attr)
    return value


def writeCSV(dirName, fileName, dataType, value, actualValue):
    """Writes values to a CSV file. If <code>actualValue</code> is
    <code>not True</code>, data for the `value` database table is written
    to. Otherwise, the table for the value's SQL data type is written to.
    :param dirName: Name of directory containing csv files  :type dirName: str
    :param fileName: The name of the csv file, used for the open() function
                                                            :type fileName: str
    :param dataType: The SQL data type e.g. BOOL, VARCHAR   :type dataType: str
    :param value: An array containing the values to be written to a line
        in the file                                         :type value: List
    :param actualValue: True if the values are to be written to the `value`
        database table                                      :type actualValue: bool
    """
    with open(dirName + 'output/'  # + 'week' + str(value[3]) + '/'
              + fileName + '.csv', mode='a', newline='') as output:
        writer = csv.writer(output, delimiter=',', quotechar='"')
        print("WRITING TO:", output.name)

        if not actualValue:
            print(dataType, value[0], value[1], value[2], value[3], value[4])
            writer.writerow([value[0], value[1], value[2], value[3], value[4]])
        else:
            print(dataType, value[0], value[1], value[2])
            writer.writerow([value[0], value[1], value[2]])


def writeCSVFiles(dirName, dataType, data, valueId, visit):
    """For all the values the of the given SQL data type, write to a csv file
    the formatted values and their IDs, and then write to the file that provides
    the relationships between the value and the entity/attributes.

    :param dirName: Name of directory containing csv files  :type dirName: str
    :param dataType: The SQL data type e.g. BOOL, VARCHAR   :type dataType: str
    :param data: All the values for that data               :type data: dict
    :param valueId: The database ID of the current value being written
                                                            :type valueId: int
    :param visit: the database ID of the visitation         :type visit: int
    :returns: The ID of the next value to be added          :rtype: int
    """
    for item in data:
        if item[3] not in ['NA','N/A','N/A`','NaN','-','--','']:
            print('\nRaw value: ' + item[3])
            value = reformatValueFromType(dataType, item[3], item[2])

            # exceptional values for specific clinical attributes
            if dataType == 'BOOL':
                # If Entity == musculoskeletal injury
                if (item[2] in [64, 67]) and (value not in [1, 0]):
                    for val in value:
                        # write varchar component for each value
                        writeCSV(dirName, 'VARCHAR', 'VARCHAR', [
                            '', valueId, val, visit
                        ], True)
                        # print('VARCHAR', valueId, val)
                        writeCSV(dirName, 'value', dataType, [
                            valueId, item[1], item[2] + 1,
                            visit, item[0]
                        ], False)
                        valueId += 1
                    value = 1

            # normal values
            writeCSV(dirName, dataType, dataType, [
                '', valueId, value, visit
            ], True)
            writeCSV(dirName, 'value', dataType, [
                valueId, item[1], item[2], visit, item[0]
            ], False)
            valueId += 1
    return valueId


def getPatients(patientIDs):
    """Get all the patient IDs from allPatients.csv that correspond to the
    patient IDs that are passed in.
    :param patientIDs: An array containing the IDs for this data set
                                                :type patientIDs: List(str)
    :returns: A list of arrays, with each patients corresponding ID str
        and their database index ID             :rtype: List(str)
    """
    allPatientsPre = readCSVFile('allPatients.csv')
    allPatientsDict = {}
    j = 0
    for idStr in allPatientsPre['Patient_ID']:
        allPatientsDict[idStr] = int(allPatientsPre['idPatient'][j])
        j += 1

    relevantPatients = []
    for p in allPatientsDict:
        if p in patientIDs:
            relevantPatients.append([p, allPatientsDict[p]])

    return relevantPatients


def extractData(fileName, relationships):
    """Converts the data in a csv file into a dictionary.
    For each column, find the attribute(s)/Id(s) then for each attr,
    go through each patient then for each data element, find and add
    that patient's data value.

    :param fileName: The name of the csv file, used for the open() function
                                                    :type fileName: str
    :param relationships: The relationships between the entities/attributes
        and their SQL data types                    :type relationships: dict
    :returns: A dictionary that associates each SQL data type with every raw
        value of that type, and the database IDs for the associated entity
        and attribute                               :rtype: dict
    """
    preDataDict = readCSVFile(fileName)
    patients = getPatients(preDataDict['CARDIGAN ID'])
    del preDataDict['CARDIGAN ID']
    dataDict = {
        'INT': [], 'VARCHAR': [], 'DECIMAL': [], 'BOOL': [],
        'TEXT': [], 'DATE': [], 'TIMESTAMP': []
    }

    for dataColName in preDataDict:
        for typesColName in relationships:
            if typesColName in dataColName:
                for attribute in relationships[typesColName]:
                    i = 0
                    for patient in patients:
                        j = 0
                        for dataValue in preDataDict[dataColName]:
                            if i == j:
                                dataDict[attribute[2]].append([
                                    patient[1], attribute[3],
                                    # idPatient, idEntity,
                                    attribute[0], dataValue
                                ])  # idAttribute, value
                            j += 1
                        i += 1
    return dataDict
