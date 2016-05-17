import sqlite3
import math
import json
import sys

def getLat(x,y):
	if y%2 == 0:
		lat = 0
	else:
		lat = 0.0886

	if x > 0:
		for i in range(0,x):
			lat += 0.0866*2*correctX(lat)
	else:
		for i in range(0,x):
			lat -= 0.0866*2*correctX(lat)

	return lat;

def getLng(x,y):
    return y*0.15

def correctX(x):
    return round(math.cos(x*0.0175)*41000/360 / 111.1111,4)

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
		xFactor = correctX(x)
		coords = [
			(x,y+0.1), # east
			(x-0.087*xFactor,y+0.05), # east-south
			(x-0.087*xFactor,y-0.05), # west-south
			(x,y-0.1), # west
			(x+0.087*xFactor,y-0.05), # west-nord
			(x+0.087*xFactor,y+0.05) # east-nord
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


# if len(sys.argv) > 1:
# 	region_id = int(sys.argv[1])
# else:
# 	print ("Enter region ID")
# 	quit()

db = sqlite3.connect('database/politsim.db')
cursor = db.execute(''' 
	SELECT 
		id,
		x,
		y
	FROM tiles
	WHERE is_land = 1;
'''.format())
rows = cursor.fetchall()

if (len(rows) == 0):
	print ("0 tiles found")
	quit()

tiles = [];
counter = 0
for row in rows:
	tiles.append(Tile(row[0],row[1],row[2]))
	counter += 1
	if (counter%1000 == 0):
		print ("loaded {} tiles".format(counter))
print ("Start imploding {} tiles".format(len(tiles)))

def tileByXY(t):
	for tile in tiles:
		if t[0] == tile.x and t[1] == tile.y:
			return tile;
	return None

def tileById(id):
	for tile in tiles:
		if id == tile.id:
			return tile;
	return None

def getPointNumbers(i):
	a = [(4,5),(5,0),(0,1),(1,2),(2,3),(3,4)]
	return a[i]

def c2i(c):
	return round(c*10000)

lines = []
counter = 0
for tile in tiles:	
	kray = []
	for i in range(0,6):
		n = tileByXY(offsetNeighbor((tile.x,tile.y),i))
		if n is None:
			kray.append(i)
	if len(kray):
		for i in kray:
			i1, i2 = getPointNumbers(i)
			c1 = tile.coords[i1]
			l1 = (c2i(c1[0]),c2i(c1[1]))
			c2 = tile.coords[i2]
			l2 = (c2i(c2[0]),c2i(c2[1]))
			line = (l1, l2)
			if not line in lines:
				lines.append(line)		
	counter += 1
	if (counter%1000 == 0):
		print ("calculated borders of {} tiles. {} lines found".format(counter, len(lines)))

print ("{} lines found".format(len(lines)))

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
for line in lines:
	if (getLineIdByCoord(line[0],line) >= 0) and (getLineIdByCoord(line[1],line) >= 0):
		pass
	elif (getLineIdByCoord(line[0],line) >= 0) or (getLineIdByCoord(line[1],line) >= 0):
		print ("Error, line have only one neighbor")
		p1, p2 = line
		p1 = (p1[0]/10000,p1[1]/10000)
		p2 = (p2[0]/10000,p2[1]/10000)
		line = (p1, p2)
		print (line)
		quit()
	else:
		print ("Error, line have no neighbors")
		p1, p2 = line
		p1 = (p1[0]/10000,p1[1]/10000)
		p2 = (p2[0]/10000,p2[1]/10000)
		line = (p1, p2)
		print (line)
		quit()
	counter += 1
	if (counter%1000 == 0):
		print ("Checked {} lines".format(counter))
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
	if (counter%100 == 0):
		print ("Adding {} lines".format(counter))

	if i in linesAdded:
		if len(linesAdded) == len(lines):
			return
		for j in range(0,len(lines)):
			if not j in linesAdded:
				return addLine(j)
	line = lines[i]
	linesAdded.append(i)
	right = getLineIdByCoord(line[1],line)
	left = getLineIdByCoord(line[0],line)
	for contur in conturs:
		if lines[right] in contur:
			contur.append(line)
			return addLine(left)

	contur = []
	contur.append(line)
	conturs.append(contur)
	return addLine(left)

counter = 0
addLine(0)

print("Finded {} conturs".format(len(conturs)))


for i in range(len(conturs)):
	for j in range(len(conturs[i])):
		p1, p2 = conturs[i][j]
		p1 = (p1[0]/10000,p1[1]/10000)
		p2 = (p2[0]/10000,p2[1]/10000)
		conturs[i][j] = p1

print (json.dumps([conturs]))
