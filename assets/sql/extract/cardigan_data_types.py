"""This script provides a description for each attribute/entity
 relationship, and the corresponding IDs and data types

Column Name :
    [[idAttribute, Attribute Name, DataType, idEntity]]
"""

clinicalDataTypes = {
    """Timestamp""":
        [[51, "Visitation Timestamp", "TIMESTAMP", 10]],
    """Date""":
        [[140, "Visitation Date", "DATE", 142]],
    """Age""":
        [[52, "Age", "INT", 11]],
    """Weight 2 (kg)""":
        [[53, "Weight", "DECIMAL", 12]],
    """Size 2 (cm)""":
        [[54, "Size", "DECIMAL", 13]],
    """BMI 2""":
        [[56, "BMI", "DECIMAL", 15]],
    """Fat %  1""":
        [[58, "Fat Perc.", "DECIMAL", 17]],
    """Muscle % 1""":
        [[60, "Muscle Perc.", "DECIMAL", 19]],
    """Visceral Fat""":
        [[61, "Visceral Fat", "DECIMAL", 20]],
    """BP""":
        [[57, "BP", "VARCHAR", 16]],
    """HR (BPM)""":
        [[59, "HR", "INT", 18]],
    """TANNER""":
        [[62, "TANNER", "INT", 21]],
    """Waist 2 (cm)""":
        [[55, "Waist", "DECIMAL", 14]],
    """Glucose 1""":
        [[93, "Glucose", "INT", 50]],
    """Insulin1""":
        [[94, "Insulin", "DECIMAL", 51]],
    """Total Cholesterol 1""":
        [[141, "Total Cholesterol", "INT", 143]],
    """HDL 1""":
        [[95, "HDL", "INT", 52]],
    """LDL 1""":
        [[96, "LDL", "DECIMAL", 53]],
    """Triglycerides 1""":
        [[97, "Triglycerides", "INT", 54]],
    """ALT 1""":
        [[98, "ALT", "INT", 55]],
    """AST 1""":
        [[99, "AST", "INT", 56]],
    """HOMA 1""":
        [[100, "HOMA", "DECIMAL", 57]],
    """HBA1C 1""":
        [[101, "HBA1C", "DECIMAL", 58]],
    """Activity enjoyment""":
        [[63, "Activity enjoyment", "INT", 22]],
    """Any musculoskeletal injury? Which one?""":
        [[64, "Any musculoskeletal injury?", "BOOL", 23]],# [65, "Which one?", "VARCHAR", 23]],
    """Evolution (If there is none, write NA)""":
        [[66, "Evolution", "VARCHAR", 24]],
    """Any programmed activity? Which one?""":
        [[67, "Any programmed activity?", "BOOL", 25]],# [68, "Which one?", "VARCHAR", 25]],
    """Days per week (If there is none, write NA)""":
        [[69, "Days per week", "INT", 26]],
    """Minutes per session (If there is none, write NA)""":
        [[70, "Minutes per seseson", "INT", 27]],
    """Leg length (cm)""":
        [[71, "Leg length", "DECIMAL", 28]],
    """Shoe size (cm)""":
        [[72, "Shoe Size", "DECIMAL", 29]],
    """Hand dominance""":
        [[73, "Hand dominance", "VARCHAR", 30]],
    """Basal O2 Sat""":
        [[74, "Basal O2 Sat", "INT", 31]],
    """Basal HR""":
        [[75, "Basal HR", "INT", 32]],
    """Basal BP""":
        [[76, "Basal BP", "VARCHAR", 33]],
    """Basal disnea (BORG)""":
        [[77, "Basal disnea (BORG)", "INT", 34]],
    """Basal Fatigue (BORG)""":
        [[78, "Basal Fatigue (BORG)", "INT", 35]],
    """Gait recorded and saved""":
        [[79, "Gait recorded and saved", "BOOL", 36]],
    """Comments""":
        [[80, "Comments", "TEXT", 37]],
    """# of laps""":
        [[81, "Number of laps", "DECIMAL", 38]],
    """Distance walked (m)""":
        [[82, "Distance walked", "DECIMAL", 39]],
    """Step count""":
        [[83, "Step count", "INT", 40]],
    """VO2 Max""":
        [[84, "VO2 Max", "DECIMAL", 41]],
    """6  walk recorded""":
        [[85, "6 min walk recorded", "BOOL", 42]],
    """Final O2 sat""":
        [[87, "Final O2 sat", "INT", 44]],
    """Final HR""":
        [[88, "Final HR", "INT", 45]],
    """Final BP""":
        [[89, "Final BP", "VARCHAR", 46]],
    """Final Disnea (BORG)""":
        [[90, "Final Disnea (BORG)", "INT", 47]],
    """Final fatigue (BORG)""":
        [[91, "Final fatigue (BORG)", "INT", 48]],
    """Dorgo Index""":
        [[92, "Dorgo Index", "DECIMAL", 49]],
    """Freq. Breakfast Week""":
        [[102, "Freq. Breakfast Week", "INT", 60]],
    """Freq. Breakfast Weekend""":
        [[103, "Freq. Breakfast Weekend", "INT", 61]],
    """Freq. Consumption of Fruit""":
        [[104, "Freq. Consumption of Fruit", "VARCHAR", 62]],
    """Freq. Consumption of Vegetables""":
        [[105, "Freq. Consumption of Vegetables", "VARCHAR", 63]],
    """Freq. Consumption of Sweets""":
        [[106, "Freq. Consumption of Sweets", "VARCHAR", 64]],
    """Freq. Consumption of Coke or Sugary Beverages""":
        [[107, "Freq. Consumption of Coke or Sugary Beverages", "VARCHAR", 65]]
}


gaitDataTypes = {
    """Timestamp""":
        [[51, "Visitation Timestamp", "TIMESTAMP", 10]],
}
