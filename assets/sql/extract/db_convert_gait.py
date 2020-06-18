import data_extract as e
import cardigan_data_types as d


# the first 2102 values have been added manually
# the next 10236 values are clinical data (found in phpMyAdmin)
currentValueId = 10237

# add all data from visitations
for num in range(1, 8):  # WHAT IS THE RANGE?
    dataDict = e.extractData(
        'Visit' + str(num) + '_6Min_.csv',  # ????
        d.gaitDataTypes,
    )
    for key in dataDict:
        currentValueId = e.writeCSVFiles(
            key, dataDict[key], currentValueId, num + 1
        )
