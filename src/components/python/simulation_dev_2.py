
istate  = '''{"K1": 1,    "K2":0.9,    "K3": 0.0,    "K4": 0.0, "K5": 0.0 }'''
initial_conncept = json.loads(istate)
W = json.loads("[[-0.05, 0.0, 0.1, 0.0, 0.0], [0.0, -0.05, 0.1, 0.0, 0.0], [0.0, 0.0, 0, 1.0, 0.0], [0.0, 0.0, 0.0, 0, 1.0],[0.0, 0.0, -0.4, -0.4, 0]]")

n = len(initial_conncept);
initial_state=initial_conncept


# simulation 
print("Szimulációs lépések:")


connection_matrix = W

l = 1
treshold=0.0001

def sigmonoid(x):
    e = np.exp(1)
    return 1/(1+(e**(-l*x)))

def mKosko(i,val):
    r = 0
    for row_i in range(len(connection_matrix)):
        if row_i != i:
            relation = connection_matrix[row_i][i]
            if relation!=0:
                r += relation*val[row_i]
    rr = r +float(val[i])
    return sigmonoid(rr)

state = list(initial_state.values())
states = []
states.append(state)
itter = 0
for abcd in range(11):
    itter+=1
    in_treshold=False
    next_state=[]
    for i, k in enumerate(state):
        next_state.append(mKosko(i,state))

    print(abcd+1, next_state)
    
    for state_index, state_val in enumerate(state):
        if abs(next_state[state_index]-state_val) < treshold and not in_treshold:
            in_treshold = True
        else:
            break
    state = next_state

    states.append(state)

    if in_treshold:
        print("Küszöbérték elérve : ",itter,"iteráció alatt.")
        break

if not in_treshold:
    print("A szimuláció srán nem talált stabil állapot ",itter,"iteráció alatt.")

fig, ax = plt.subplots()

for index in range(len(state)):
    s=[]
    for state_index in range(len(states)):
        s.append(states[state_index][index])
    plt.plot(s,label="K"+str(index+1))
    



plt.xlabel('Iterációk')
plt.ylabel('Érték')
plt.title('Szimuláció')
plt.legend(loc='lower right')

display(fig, target="mpl")

