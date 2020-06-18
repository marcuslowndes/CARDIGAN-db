import data_extract as e
import cardigan_data_types as d


# the first 2102 values have been added manually
currentValueId = 2103

# add all data from visitations 1,2 and 3
for num in range(1, 4):
    dataDict = e.extractData(
        'PAIDOS Visit ' + str(num) + '.csv',
        d.clinicalDataTypes,
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            key, dataDict[key], currentValueId, num + 1
        )

# SKIP VISITATION 4, UNTRANSLATED

# add all data from visitations 5, 5 and 7
for num in range(5, 7):
    dataDict = e.extractData(
        'PAIDOS Visit ' + str(num) + '.csv',
        d.clinicalDataTypes
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            key, dataDict[key], currentValueId, num + 1
        )

