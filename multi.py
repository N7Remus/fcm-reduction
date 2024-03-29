import zlib
import json

matrixpath='ajax.php'

f = open(matrixpath, 'rb')
decompressed_data = zlib.decompress(f.read())

#print (decompressed_data)

y = json.loads(decompressed_data)

initial_conncept = y["init_state"]

n = len(initial_conncept);

W = y["connection_matrix"]

E=0.2

print("Matrix loaded : "+matrixpath)
print("E value : "+str(E))
print("Matrix size : "+str(n)+"x"+str(n))

def isNearC(i, j, E):
    sum = 0
    for k in range(n):
        if k != i and k != j:
            #print("Line i",i,"j",j,"k",k," w1",(W[i][k] - W[j][k]),"w2",(W[k][i] - W[k][j]))
            sum += (W[i][k] - W[j][k]) ** 2
            sum += (W[k][i] - W[k][j]) ** 2
 
    
   
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

import multiprocessing
import random
from functools import partial
from datetime import datetime

def worker(task_queue, result_queue, task_complete_event):
    """Function executed by each worker process."""
    while True:
        task = task_queue.get()
        if task is None:
            break
        # Perform the task
        result = task()
        result_queue.put(result)
        task_complete_event.set()  # Signal task completion

def task_generator(tasks, task_queue):
    """Generates tasks and puts them into the task queue."""
    for task in tasks:
        task_queue.put(task)

def generate_clusters(i,E):
    """Generate a random number."""
    print("Starting cluster [",str(i),"] Current Time =", datetime.now().strftime("%H:%M:%S"))
    return buildCluster(i,E)

def main():
    # Number of worker processes to create
    num_tasks = n
    num_processes = min(multiprocessing.cpu_count(),num_tasks)

    # Create task queue and result queue
    task_queue = multiprocessing.Queue()
    result_queue = multiprocessing.Queue()

    # Generate tasks
   
    # tasks = [partial( generate_clusters,_,E) for _ in range(num_tasks)]
    tasks = [partial( generate_clusters,_,E) for _ in range(n)]

    # Put tasks into the queue
    task_generator(tasks, task_queue)

    # Event to track task completion
    task_complete_event = multiprocessing.Event()

    # Create worker processes
    processes = []
    
    for _ in range(num_processes):
        p = multiprocessing.Process(target=worker, args=(task_queue, result_queue, task_complete_event))
        p.start()
        processes.append(p)

    # Wait for all tasks to complete
    while True:
        if task_complete_event.wait(timeout=1):  # Check every second
            task_complete_event.clear()
        else:
            # If no tasks completed within timeout, check if all tasks are done
            if task_queue.empty():
                break

    # Terminate worker processes
    for _ in range(num_processes):
        task_queue.put(None)  # Signal to stop worker

    # Join worker processes
    for p in processes:
        p.join()

    # Collect results
    results = []
    while not result_queue.empty():
        result = result_queue.get()
        results.append(result)

    print("All tasks completed.")
    # Store the JSON data in a file
    with open("res.json", "w") as file:
        json.dump(results, file)
    print("Results:", results)

if __name__ == "__main__":
    main()