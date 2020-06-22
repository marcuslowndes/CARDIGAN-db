import cardigan_data_extract as e
import cardigan_db_relationships as d


# the first 2102 values have been added manually
# the next 10236 values are clinical data
currentValueId = 13946
dirName = 'gait_6min/'
subtypes = ['_Beginning', '_Middle', '_End']


# add all data from visitations
for num in range(1, 7):
    for part in subtypes:
        relationships = d.get_dataRelationships_gait(part)
        dataDict = e.extractData(
            dirName + 'Visit' + str(num) + '_6Min'
            + part + '.csv', relationships,
        )
        for key in dataDict:
            currentValueId = e.writeCSVFiles(
                dirName, key, dataDict[key],
                currentValueId, num + 1
            )

# filename lacks '_6min'
for part in subtypes:
    relationships = d.get_dataRelationships_gait(part)
    dataDict = e.extractData(
        dirName + 'Visit' + str(7)  # + '_6Min'
        + part + '.csv', relationships,
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            dirName, key, dataDict[key],
            currentValueId, 8
        )