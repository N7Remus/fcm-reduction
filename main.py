from fcmpy import ExpertFcm, FcmSimulator, FcmIntervention
import numpy as np
import matplotlib.pyplot as plt
import os
import pandas as pd
fcm = ExpertFcm()

fcm.linguistic_terms = {
                        '-VH': [-1, -1, -0.75],
                        '-H': [-1, -0.75, -0.50],
                        '-M': [-0.75, -0.5, -0.25],
                        '-L': [-0.5, -0.25, 0],
                        '-VL': [-0.25, 0, 0],
                        'NA': [-0.001, 0, 0.001],
                        '+VL': [0, 0, 0.25],
                        '+L': [0, 0.25, 0.50],
                        '+M': [0.25, 0.5, 0.75],
                        '+H': [0.5, 0.75, 1],
                        '+VH': [0.75, 1, 1]
                        }

fcm.universe = np.arange(-1, 1.05, .05)

fcm.fuzzy_membership = fcm.automf(method='trimf')

mfs = fcm.fuzzy_membership

fig = plt.figure(figsize= (10, 5))
axes = plt.axes()

for i in mfs:
    axes.plot(fcm.universe, mfs[i], linewidth=0.4, label=str(i))
    axes.fill_between(fcm.universe, mfs[i], alpha=0.5)

axes.legend(bbox_to_anchor=(0.95, 0.6))

axes.spines['top'].set_visible(False)
axes.spines['right'].set_visible(False)
axes.get_xaxis().tick_bottom()
axes.get_yaxis().tick_left()
plt.tight_layout()
# Mat plotlib megjelenítése
# plt.show()


data = fcm.read_data(file_path= os.path.abspath('data_test.csv'),
                     sep_concept='->', csv_sep=';')
entropy = fcm.entropy(data)
weight_matrix = fcm.build(data=data, implication_method='Larsen')
sim = FcmSimulator()
C1 = [0.0, 0.0, 0.6, 0.9, 0.0, 0.0, 0.0, 0.8]
C2 = [0.1, 0.0, 0.0, 0.0, 0.0, 0.0, 0.2, 0.5]
C3 = [0.0, 0.7, 0.0, 0.0, 0.9, 0.0, 0.4, 0.1]
C4 = [0.4, 0.0, 0.0, 0.0, 0.0, 0.9, 0.0, 0.0]
C5 = [0.0, 0.0, 0.0, 0.0, 0.0, -0.9, 0.0, 0.3]
C6 = [-0.3, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0]
C7 = [0.0, 0.0, 0.0, 0.0, 0.0, 0.8, 0.4, 0.9]
C8 =[0.1, 0.0, 0.0, 0.0, 0.0, 0.1, 0.6, 0.0]

weight_matrix = pd.DataFrame([C1,C2, C3, C4, C5, C6, C7, C8],
                    columns=['C1','C2','C3','C4','C5','C6','C7','C8'])
init_state = {'C1': 1, 'C2': 1, 'C3': 0, 'C4': 0, 'C5': 0,
                    'C6': 0, 'C7': 0, 'C8': 0}
res_mK = sim.simulate(initial_state=init_state, weight_matrix=weight_matrix, transfer='sigmoid', inference='mKosko', thresh=0.001, iterations=50, l=1)
#plt.figure()
res_mK.plot(figsize=(15, 10))
plt.legend(bbox_to_anchor=(0.97, 0.94))

plt.xlabel('Simulation Steps')
plt.ylabel('Initial States')
plt.show()


inter = FcmIntervention(FcmSimulator)
inter.initialize(initial_state=init_state, weight_matrix=weight_matrix,
                        transfer='sigmoid', inference='mKosko', thresh=0.001, iterations=50, l=1)
inter.add_intervention('intervention_1', impact={'C1':-.3, 'C2' : .5}, effectiveness=1)
inter.add_intervention('intervention_2', impact={'C4':-.5}, effectiveness=1)
inter.add_intervention('intervention_3', impact={'C5':-1}, effectiveness=1)
inter.test_intervention('intervention_1')
inter.test_intervention('intervention_2')
inter.test_intervention('intervention_3')

print(inter.test_results['intervention_1'])
print(inter.comparison_table)
print(inter.equilibriums)


