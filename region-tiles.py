import sqlite3
import math
import json
import sys

def printProgress (iteration, total, prefix = '', suffix = '', decimals = 2, barLength = 100):
    """
    Call in a loop to create terminal progress bar
    @params:
        iteration   - Required  : current iteration (Int)
        total       - Required  : total iterations (Int)
        prefix      - Optional  : prefix string (Str)
        suffix      - Optional  : suffix string (Str)
    """
    filledLength    = int(round(barLength * iteration / float(total)))
    percents        = round(100.00 * (iteration / float(total)), decimals)
    bar             = '#' * filledLength + '-' * (barLength - filledLength)
    sys.stdout.write('%s [%s] %s%s %s\r' % (prefix, bar, percents, '%', suffix)),
    sys.stdout.flush()
    if iteration == total:
        print("\n")

def getLat(x,y):
	if y%2 == 0:
		lat = 0
	else:
		lat = 886

	if x > 0:
		for i in range(0,x):
			lat += 866*2*correctX(lat)
	else:
		for i in range(0,x):
			lat -= 866*2*correctX(lat)

	return lat;

def getLng(x,y):
    return y*1500

def correctX(x):
    return round(math.cos(x*0.0175)*1.025,4)

class Tile:
	def __init__(self,id,x,y):
		self.kray = []
		self.id = id
		self.x = x
		self.y = y
		self.coords = self.calcCoords()

	def calcCoords(self):
		x = getLat(self.x,self.y)
		y = getLng(self.x,self.y)
		xFactor = round(866*correctX(x))
		coords = [
			(x,y+1000), # east
			(x-xFactor,y+500), # east-south
			(x-xFactor,y-500), # west-south
			(x,y-1000), # west
			(x+xFactor,y-500), # west-nord
			(x+xFactor,y+500) # east-nord
		]

		return coords;

directions = (
	( #	    nord      n-e       s-e      south      s-w       n-w
		( (+1,  0), ( 0, +1), (-1, +1), (-1,  0), (-1, -1), ( 0, -1) ), #
		( (+1,  0), (+1, +1), ( 0, +1), (-1,  0), ( 0, -1), (+1, -1) ), #
	), (
		( (+1,  0), ( 0, +1), (-1, +1), (-1,  0), (-1, -1), ( 0, -1) ), #
		( (+1,  0), (+1, +1), ( 0, +1), (-1,  0), ( 0, -1), (+1, -1) )  #
    )  
)

def offsetNeighbor(h, d):
    parityX = h[0] & 1
    parityY = h[1] & 1
    off = directions[parityX][parityY][d]
    return (h[0] + off[0], h[1] + off[1])

interactiveMode = False
if len(sys.argv) > 1:
        if sys.argv[1] in ('-i', '--interactive'):
                interactiveMode = True

db = sqlite3.connect('database/politsim.sqlite')
cursor = db.execute(''' 
	SELECT 
		id,
		x,
		y
	FROM tiles
	WHERE is_land = 1;
'''.format())
rows = cursor.fetchall()
rowsLength = len(rows)

if (rowsLength == 0):
        if interactiveMode:
                print ("0 tiles found")
	quit()

tiles = [];
counter = 0
if interactiveMode:
        printProgress(0,rowsLength,"loading tiles: ")
for row in rows:
	tiles.append(Tile(row[0],row[1],row[2]))
	counter += 1
        if interactiveMode:
                printProgress(counter,rowsLength,"loading tiles: ")

tilesLength = len(tiles)

if interactiveMode:
        print ("Start imploding {} tiles".format(tilesLength))

def tileByXY(t):
	for tile in tiles:
		if t[0] == tile.x and t[1] == tile.y:
			return tile;
	return None

def isIssetTileByXY(t):
	for tile in tiles:
		if t[0] == tile.x and t[1] == tile.y:
			return True;
	return False

def tileById(id):
	for tile in tiles:
		if id == tile.id:
			return tile;
	return None

def getPointNumbers(i):
	a = [(4,5),(5,0),(0,1),(1,2),(2,3),(3,4)]
	return a[i]

lines = []
counter = 0
if interactiveMode:
        printProgress(0,tilesLength,"get borders: ")
for tile in tiles:	
	kray = []
	for i in range(0,6):
		if not isIssetTileByXY(offsetNeighbor((tile.x,tile.y),i)):
			kray.append(i)
	if len(kray):
		for i in kray:
			i1, i2 = getPointNumbers(i)
			line = (tile.coords[i1], tile.coords[i2])
			if not line in lines:
				lines.append(line)		
	counter += 1
        if interactiveMode:
                printProgress(counter,tilesLength,"get borders: ")

linesLength = len(lines)

if interactiveMode:
        print ("{} lines found".format(linesLength))

def pointEquals(p1, p2):
	d = 10
	return (abs(p1[0]-p2[0]) <= d) and (abs(p1[1]-p2[1]) <= d)

def getLineIdByCoord(t, no):
	for i in range(len(lines)):
		line = lines[i]
		if (line == no):
			continue
		if pointEquals(line[0],t) or pointEquals(line[1],t):
			return i
	return -1

counter = 0
if interactiveMode:
        printProgress(0,linesLength,"check lines: ")
for line in lines:
	if (getLineIdByCoord(line[0],line) >= 0) and (getLineIdByCoord(line[1],line) >= 0):
		pass
	elif (getLineIdByCoord(line[0],line) >= 0) or (getLineIdByCoord(line[1],line) >= 0):
                if interactiveMode:
                        print ("Error, line have only one neighbor")
                        p1, p2 = line
                        p1 = (p1[0]/10000,p1[1]/10000)
                        p2 = (p2[0]/10000,p2[1]/10000)
                        line = (p1, p2)
                        print (line)
		quit()
	else:
                if interactiveMode:                
                        print ("Error, line have no neighbors")
                        p1, p2 = line
                        p1 = (p1[0]/10000,p1[1]/10000)
                        p2 = (p2[0]/10000,p2[1]/10000)
                        line = (p1, p2)
                        print (line)
		quit()
	counter += 1        
        if interactiveMode:
                printProgress(counter,linesLength,"check lines: ")

if interactiveMode:
        print ("ALL lines checked")

# for i in range(len(lines)):
# 	p1, p2 = lines[i]
# 	p1 = (p1[0]/10000,p1[1]/10000)
# 	p2 = (p2[0]/10000,p2[1]/10000)
# 	lines[i] = (p1,p2)
# print (json.dumps(lines))

conturs = []
linesAdded = []

def addLine(i):
	global counter

	counter += 1
        if interactiveMode:
            printProgress(counter,linesLength,"adding lines: ")

	if i in linesAdded:
		if len(linesAdded) == len(lines):
			return -1
		for j in range(0,len(lines)):
			if not j in linesAdded:
				return j
	line = lines[i]
	linesAdded.append(i)
	right = getLineIdByCoord(line[1],line)
	left = getLineIdByCoord(line[0],line)
	for contur in conturs:
		if lines[right] in contur:
			contur.append(line)
			return left

	contur = []
	contur.append(line)
	conturs.append(contur)
	return left

counter = 0
n = 0
if interactiveMode:
        printProgress(0,len(lines)+1,"adding lines: ")
while n >= 0:
	n = addLine(n)

if interactiveMode:
        print("Finded {} conturs".format(len(conturs)))


for i in range(len(conturs)):
	for j in range(len(conturs[i])):
		p1, p2 = conturs[i][j]
		p1 = (p1[0]/10000,p1[1]/10000)
		p2 = (p2[0]/10000,p2[1]/10000)
		conturs[i][j] = p1

#print (json.dumps([conturs]))
f = open('all-lands.json', 'w')
f.write(json.dumps([conturs]))

if interactiveMode:
        print("conturs writed to all-lands.json")