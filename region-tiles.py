import sqlite3;
import math;

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
		self.id = id
		self.x = x
		self.y = y
		self.coords = self.calcCoords()

	def calcCoords(self):
		x = getLat(self.x,self.y)
		y = getLng(self.x,self.y)
		coords = []
		for i in range(0,6):
			angle = 2*3.14159*i/6+3.14159/2
			tx = round(x + 0.1*math.cos(angle)*correctX(x),4)
			ty = round(y + 0.1*math.sin(angle),4)
			coords.append((tx,ty))

		return coords;

directions = (
	( #	    north      n-e       s-e      south      s-w       n-w
		( (+1,  0), (-1, +1), ( 0, -1), (-1, -1), (-1,  0), ( 0, +1) ),
		( (+1,  0), (+1, +1), ( 0, +1), (-1,  0), ( 0, -1), (+1, -1) ), #
	), (
		( (+1,  0), ( 0, +1), (-1, +1), (-1,  0), (-1, -1), ( 0, -1) ), #
		( (+1, +1), (+1,  0), ( 0, -1), (-1,  0), (+1, -1), ( 0, +1) )
    )
)

def offset_neighbor(h, d):
    parityX = h[0] & 1
    parityY = h[1] & 1
    off = directions[parityX][parityY][d]
    return (h[0] + off[0], h[1] + off[1])



db = sqlite3.connect('database/politsim.db')
cursor = db.execute(''' 
	SELECT 
		id,
		x,
		y
	FROM tiles
	WHERE region_id = 316;
'''.format(id))
rows = cursor.fetchall()

tiles = [];
for row in rows:
	tiles.append(Tile(row[0],row[1],row[2]))

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

tile = tileById(1041)
print (tile.id)

for i in range(0,6):
    n = tileByXY(offset_neighbor((tile.x,tile.y),i))
    if n:
    	print (n.id)