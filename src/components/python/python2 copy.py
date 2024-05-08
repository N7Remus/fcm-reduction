from pyscript import display
from datetime import datetime
import pandas as pd
import networkx as nx
import matplotlib.pyplot as plt
import csv
import requests
import io



def make_label_dict(labels):
    l = {}
    for i, label in enumerate(labels):
        l[i] = label
    return l


url="https://fcm.remai.hu/data/data_test5-wiki.csv"
r = requests.get(url)
s=r.content
input_data=pd.read_csv(io.StringIO(s.decode('utf-8')))
print(input_data.values)
G = nx.Graph(input_data.values)
d_reader = csv.DictReader(input_data)
headers = d_reader.fieldnames
labels=make_label_dict(headers)
edge_labels = dict( ((u, v), d["weight"]) for u, v, d in G.edges(data=True) )
pos = nx.spring_layout(G)
nx.draw(G, pos)
nx.draw_networkx_edge_labels(G, pos, edge_labels=edge_labels)
nx.draw(G,pos,node_size=500, labels=labels, with_labels=True)
print("OK")
plt.show()