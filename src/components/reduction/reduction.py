n = len(W[0])

def isNearC(i, j, E):
    sum = 0
    for k in range(n):
        if k != i and k != j:
            sum += (float(W[i][k]) - float(W[j][k])) ** 2
            sum += (float(W[k][i]) - float(W[k][j])) ** 2
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

def equal_ignore_order(a, b):
    """ Use only when elements are neither hashable nor sortable! """
    unmatched = list(b)
    for element in a:
        try:
            unmatched.remove(element)
        except ValueError:
            return False
    return not unmatched

def buildAllClusters(E):
    clusters = []
    for i in range(n):
        K = buildCluster(i,E)
        addable=True
        for c in clusters:
            if equal_ignore_order(c,K):
                addable=False
        if addable:
            clusters.append(K)
    return clusters



print("Az elkészíthető klaszterek : ")

clusters = buildAllClusters(E)
print(clusters)
clustername = "M"
def getWeight(Ka,Kb):
    count = 0
    sum = 0
    for i in Ka:
        for j in Kb:
            if i!=j:
                count += 1
                sum += float(W[i][j])
    if count!=0:
        #print(sum,count)
        return sum/count
    return 0
print("Súlyok kiszámítása:")

Ka_i = 0
Kv_i = 0

for Ka in clusters:
    Ka_i += 1
    Kb_i = 0
    for Kb in clusters:
        Kb_i += 1
        print("K"+str(Ka_i),"->","K"+str(Kb_i),getWeight(Ka,Kb))
        


display()

