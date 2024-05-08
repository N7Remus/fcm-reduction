initial_conncept = json.loads(init_state_json)
W = json.loads(conn_mx_json)
n = len(initial_conncept);



# simulation 
print("Szimulációs lépések:")

initial_state = json.loads(init_state_json)
connection_matrix = json.loads(conn_mx_json)



def sigmonoid(x):
    e = np.exp(1)
    return 1/(1+(e**(-l*x)))

def mKosko(i,val):
    r = 0
    for row_i in range(len(connection_matrix)):
        if row_i != i:
            relation = float(connection_matrix[row_i][i])
            if relation!=0:
                r += relation*float(val[row_i])
    rr = r +float(val[i])
    return sigmonoid(rr)

state = list(initial_state.values())
states = []
states.append(state)
itter = 0
for abcd in range(itterations):
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


