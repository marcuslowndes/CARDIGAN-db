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


def readCSVFile(filename):
    """Reads a CSV file and represents the data as a dictionary
    :param filename: The name of the csv file, used for the open() function
    :type filename: str
    :returns: a dictionary, where each key represents a column title and
        each value represents an array of values of the corresponding column
    :rtype: dict
    """
    allData = {}

    with open(filename) as csvDataFile:
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
     where it must be manipulated.
    """
    if dataStr in ['Yes', 'YES', 'Si', 'SÍ', 'yes']:
        return 1
    elif dataStr in ['No', 'NO', 'no']:
        return 0
    else:
        # these attributes are an exception to the rule
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
    """A switch statement to reformat values into their
     respective data types.
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


def writeCSV(fileName, dataType, value, actualValue):
    with open('week' + str(value[3]) + '\\' + fileName
              + '.csv', mode='a', newline='') as output:
        writer = csv.writer(output, delimiter=',', quotechar='"')

        if not actualValue:
            print(dataType, value[0], value[1], value[2], value[3], value[4])
            writer.writerow([value[0], value[1], value[2], value[3], value[4]])
        else:
            print(dataType, value[0], value[1], value[2])
            writer.writerow([value[0], value[1], value[2]])


def writeCSVFiles(dataType, data, valueId, visit):
    for item in data:
        idPatient = item[0]
        idEntity = item[1]
        idAttribute = item[2]

        if item[3] not in ['NA','N/A','-','--','']:
            print('\nraw: ' + item[3])
            value = reformatValueFromType(dataType, item[3], idAttribute)

            # exceptional values
            if dataType == 'BOOL':
                # If Entity == musculoskeletal injury
                if (idAttribute in [64, 67]) and (value not in [1, 0]):
                    for val in value:
                        # write varchar component for each value
                        writeCSV('VARCHAR', 'VARCHAR', ['', valueId, val], True)
                        # print('VARCHAR', valueId, val)
                        writeCSV('value', dataType, [
                            valueId, idEntity, idAttribute + 1, visit, idPatient
                        ], False)
                        valueId += 1
                    value = 1

            # normal values
            writeCSV(dataType, dataType, ['', valueId, value], True)
            writeCSV('value', dataType, [
                valueId, idEntity, idAttribute, visit, idPatient
            ], False)
            valueId += 1
    return valueId


def getPatients(patientIDs):
    """Get all the patient IDs from allPatients.csv that correspond to the
    patient IDs that are passed in.
    :param patientIDs: An array containing the IDs for this data set
    :type patientIDs: List(str)
    :returns: A list of arrays, with each patients corresponding ID str
        and their database index ID
    :rtype: List(str)
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


def extractData(fileName, types):
    """Converts the data in a csv file into a dictionary
    For each column, find the attribute(s)/Id(s)
    then for each attr, go through each patient
    then for each data element, find and add that patient's data value
    """
    preDataDict = readCSVFile(fileName)
    patients = getPatients(preDataDict['CARDIGAN ID'])
    del preDataDict['CARDIGAN ID']
    dataDict = {
        'INT': [], 'VARCHAR': [], 'DECIMAL': [], 'BOOL': [],
        'TEXT': [], 'DATE': [], 'TIMESTAMP': []
    }

    for dataColName in preDataDict:
        for typesColName in types:
            if dataColName == typesColName:
                for attribute in types[typesColName]:
                    i = 0
                    for patient in patients:
                        j = 0
                        for dataValue in preDataDict[dataColName]:
                            if i == j:
                                dataDict[attribute[2]].append(
                                    [patient[1], attribute[3],  # idPatient, idEntity,
                                     attribute[0], dataValue]   # idAttribute, value
                                )
                            j += 1
                        i += 1
    return dataDict
