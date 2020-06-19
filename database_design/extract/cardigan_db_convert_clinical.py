import cardigan_data_extract as e
import cardigan_db_relationships as d


# the first 2102 values are added manually
currentValueId = 2103
dirName = 'clinical/'
relationships = d.dataRelationships_clinical

# add all data from visitations 1, 2 and 3 (ignoring pre-consultation)
for num in range(1, 4):
    dataDict = e.extractData(
        dirName + 'PAIDOS Visit ' + str(num) + '.csv', relationships
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            dirName, key, dataDict[key], currentValueId, num + 1
        )

# SKIP VISITATION 4, UNTRANSLATED

# add all data from visitations 5, 6 and 7
for num in range(5, 8):
    dataDict = e.extractData(
        dirName + 'PAIDOS Visit ' + str(num) + '.csv', relationships
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            dirName, key, dataDict[key], currentValueId, num + 1
        )
