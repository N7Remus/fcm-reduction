
def isNearC(i, j, E):
    sum = 0
    for k in range(n):
        if k != i and k != j:
            sum += (W[i][k] - W[j][k]) ** 2
            sum += (W[k][i] - W[k][j]) ** 2
    if (sum / ((n - 2) * 8) < E):
        return True
    else:
        return False
def buildCluster(initial_conncept, E):
    K = [initial_conncept]
    currentK = initial_conncept
    for i in range(n):
        if i != initial_conncept:
            member = True
            while member:
                j = currentK
                member = isNearC(j, i, E)
                if member:
                    if i not in K:
                        K.append(i)
                        currentK=i
                    else:
                        member=False
    return K

print("Redukció futtatása:")

klusters={}
kluster_members = []
for d in range(n):
    if d not in kluster_members: 
        b=buildCluster( d ,  0.002 )
        for member in b:
            kluster_members.append(member)
        klusters["K"+str(d+1)] = b
        print("K"+str(d+1))
        for a in b:
            print("C"+str(a+1))


print(klusters)

NEW_W = []
cluster_incoming={}
cluster_incoming_counter={}

cluster_outgoing={}
cluster_outgoing_counter={}

cluster_own={}
cluster_own_counter={}

y = {0: 1, 1: 1, 2: 1, 3: 1}
#y = {0: 1, 2: 1}
#y = { 1: 1, 3: 1}

clustername = "M"
# get outgoint connections


def getWeight(Ka,Kb):
    count = 0
    sum = 0
    for i in Ka:
        for j in Kb:
            if i!=j:
                count += 1
                sum += W[i][j]
    if count!=0:
        print(sum,count)
        return sum/count
    return 0

for from_Ka,Ka in klusters.items():
    W_line = []
    for to_Kb,Kb in klusters.items():
        print(from_Ka,to_Kb,getWeight(Ka,Kb))
        W_line.append(getWeight(Ka,Kb))
        
    NEW_W.append(W_line)

print(NEW_W)
display()

