plt.rcParams["figure.figsize"] = [10,10]
plt.rcParams["figure.autolayout"] = True
fig, ax = plt.subplots()

for index in range(len(state)):
    s=[]
    for state_index in range(len(states)):
        s.append(float(states[state_index][index]))
    plt.plot(s,label="C"+str(index+1))


plt.xlabel('Iterációk')
plt.ylabel('Érték')
plt.title('Szimuláció')
plt.legend(loc='lower right')

display(fig, target="mpl")
