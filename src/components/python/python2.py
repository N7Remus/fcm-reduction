import json
from pyscript import display

init_state_json  = '''{
    "C1": 0.85,
    "C2": 0.7,
    "C3": 0.5,
    "C4": 0.6,
    "C5": 0.0,
    "C6": 0,
    "C7": 0
}'''

conn_mx_json = '''[
    [
        0,
        0,
        0.1,
        0,
        0,
        0,
        0
    ],
    [
        0,
        0,
        0,
        0.1,
        0,
        0,
        0
    ],
    [
        -0.2,
        0,
        0,
        0,
        0.2,
        0,
        0
    ],
    [
        0,
        -0.2,
        0,
        0,
        0.2,
        0,
        0
    ],
    [
        0,
        0,
        0,
        0,
        0,
        1,
        0
    ],
    [
        0,
        0,
        0,
        0,
        0,
        0,
        1
    ],
    [
        0,
        0,
        0,
        0,
        -0.4,
        -0.4,
        0
    ]
]'''



initial_conncept = json.loads(init_state_json)
W = json.loads(conn_mx_json)
n = len(initial_conncept);

def isNearC(i, j, E):
    sum = 0
    for k in range(n):
        if k != i and k != j:
            #print("Line i",i,"j",j,"k",k," w1",(W[i][k] - W[j][k]),"w2",(W[k][i] - W[k][j]))
            sum += (W[i][k] - W[j][k]) ** 2
            sum += (W[k][i] - W[k][j]) ** 2
    #print("Line i",(sum / ((n - 2) * 8)))
    if (sum / ((n - 2) * 8) < E):
        return True
    else:
        return False
def buildCluster(initial_conncept, E):
    K = {initial_conncept:1}
    currentK = initial_conncept
    for i in range(n):
        if i != initial_conncept:
            member = True
            while member:
                j = currentK
                member = isNearC(j, i, E)
                if member:
                    if i not in K.keys():
                        K[i]=1
                        currentK=i
                    else:
                        member=False
            
    return K;

klusters={}
for d in range(n):
    print("buildCluster(d, 0.027)",d, "C"+str(d+1),"\n")
    b = buildCluster(d, 0.002);
    klusters["C"+str(d+1)] = b
    if len(b)>1:
        print("K"+str(d+1))
        for a in b.keys():
            print("C"+str(a+1))


display()

