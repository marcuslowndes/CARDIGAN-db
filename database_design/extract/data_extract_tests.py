import cardigan_data_extract as e
import cardigan_db_relationships as d
"""
# TEST 1
print(e.readCSVFile('PAIDOS Visit 1.csv'))

# TEST 2
print(e.getPatients([
    'D02', 'D03', 'HC01', 'HC02', 'HC03',
    'HC04', 'HC05', 'HC06', 'HC07'
]))

# TEST 3
print(e.extractData(
    'PAIDOS Visit 1.csv',
    d.dataRelationships_clinical
))"""

# TEST 4
print(e.extractData(
    'gait_6min/' + 'Visit' + str(1) + '_6Min'
    + '_Beginning' + '.csv',
    d.get_dataRelationships_gait('_Beginning'),
))
