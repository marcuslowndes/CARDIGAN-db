import data_extract as e
import cardigan_data_types as d

# # TEST 1
# print(e.readCSVFile('PAIDOS Visit 1.csv'))

# # TEST 2
# print(e.getPatients([
#     'D02', 'D03', 'HC01', 'HC02', 'HC03',
#     'HC04', 'HC05', 'HC06', 'HC07'
# ]))

# TEST 3
print(e.extractData(
    'PAIDOS Visit 1.csv',
    d.clinicalDataTypes
))