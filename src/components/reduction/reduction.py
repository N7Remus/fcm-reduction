n = len(W[0])
E = 0.02

def isNearC(i, j, E):
    sum = 0
    for k in range(n):
        if k != i and k != j:
            sum += (W[i][k] - W[j][k]) ** 2
            sum += (W[k][i] - W[k][j]) ** 2
    if n>2 and (sum / ((n - 2) * 8) < E):
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

print("Redukciós paraméterek:")

print("n:",n)

print("E:",E)



print("Redukció futtatása:")

klusters={}
kluster_members = []
for d in range(n):
    if d not in kluster_members: 
        b = buildCluster( d ,  E )
        for member in b:
            kluster_members.append(member)
        klusters["K"+str(d+1)] = b
        print("K"+str(d+1),"tagjai : " , end='')
        for a in b:
            print("C"+str(a+1),end=', ')
        print()

print("Az elkészíthető klaszterek : ")
print(klusters)

NEW_W = []
clustername = "M"
def getWeight(Ka,Kb):
    count = 0
    sum = 0
    for i in Ka:
        for j in Kb:
            if i!=j:
                count += 1
                sum += W[i][j]
    if count!=0:
        #print(sum,count)
        return sum/count
    return 0
print("Súlyok kiszámítása:")
for from_Ka,Ka in klusters.items():
    W_line = []
    for to_Kb,Kb in klusters.items():
        print(from_Ka,"->",to_Kb,getWeight(Ka,Kb))
        W_line.append(getWeight(Ka,Kb))
    NEW_W.append(W_line)
print("Új kapcsolati mátrix:")
print(NEW_W)

display()

