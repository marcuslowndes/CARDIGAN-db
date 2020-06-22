import cardigan_data_extract as e
import cardigan_db_relationships as d


# the first 2102 values have been added manually
# the next 10236 values are clinical data
# the next 19498 values are gait, 6 min data
currentValueId = 29735
dirName = 'gait_10m/'
subtypes = ['UP', 'DOWN']


# add all data from visitations
for num in range(1, 7):
    for part in subtypes:
        relationships = d.get_dataRelationships_gait(part)
        dataDict = e.extractData(
            dirName + 'week' + str(num)
            + '/CardiganOutParAllV' + str(num)
            + part + '.csv', relationships
        )
        for key in dataDict:
            currentValueId = e.writeCSVFiles(
                dirName, key, dataDict[key],
                currentValueId, num + 1
            )