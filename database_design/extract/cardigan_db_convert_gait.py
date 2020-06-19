import cardigan_data_extract as e
import cardigan_db_relationships as d


# the first 2102 values have been added manually
# the next 10236 values are clinical data
currentValueId = 10237
dirName = 'gait_6min/'

# add all data from visitations
for num in range(1, 7):  # WHAT IS THE RANGE?
    dataDict = e.extractData(
        dirName + 'Visit' + str(num) + '_6Min_.csv',  # ????
        d.dataRelationships_gait,
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            dirName, key, dataDict[key], currentValueId, num + 1
        )
