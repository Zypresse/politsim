#!/usr/bin/python

import sqlite3
import math
import json
import sys
import os

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
		xFactor = round(0.0866*correctX(x),4)
		coords = [
			(x,y+0.1), # east
			(x-xFactor,y+0.05), # east-south
			(x-xFactor,y-0.05), # west-south
			(x,y-0.1), # west
			(x+xFactor,y-0.05), # west-nord
			(x+xFactor,y+0.05) # east-nord
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

path = os.path.realpath(os.path.dirname(__file__))

db = sqlite3.connect(path+'/database/politsim.sqlite')


def getPointNumbers(i):
	a = [(4,5),(5,0),(0,1),(1,2),(2,3),(3,4)]
	return a[i]

def pointEquals(p1, p2):
    d = 10
    return (abs(p1[0]-p2[0]) <= d) and (abs(p1[1]-p2[1]) <= d)


R = 100
def implodeTiles(fromX, fromY):

    cursor = db.execute(''' 
        SELECT 
            id,
            x,
            y
        FROM tiles
        WHERE is_land = 1 AND x >= {} AND x < {} AND y >= {} AND y < {};
    '''.format(fromX,fromX+R,fromY,fromY+R))
    rows = cursor.fetchall()
    rowsLength = len(rows)

    if (rowsLength == 0):
        if interactiveMode:
            print ("0 tiles found")
        return []
    print(fromX,fromX+R,fromY,fromY+R)
    tiles = {}
    counter = 0
    # if interactiveMode:
        # printProgress(0,rowsLength,"loading tiles: ")
    for row in rows:
        tile = Tile(row[0],row[1],row[2])

        if not tile.x in tiles:
            tiles[tile.x] = {}
            tiles[tile.x][tile.y] = tile
                
        counter += 1
        # if interactiveMode:
            # printProgress(counter,rowsLength,"loading tiles: ")

    tilesLength = rowsLength

    if interactiveMode:
        print ("Start imploding {} tiles".format(tilesLength))

    lines = []
        
    def isIssetTileByXY(t):
        if t[0] in tiles:
            if t[1] in tiles[t[0]]:                        
                return True;
        return False

    counter = 0
    # if interactiveMode:
        # printProgress(0,tilesLength,"get borders: ")	
    for x in tiles:
        for y in tiles[x]:
            tile = tiles[x][y]
            kray = []

            for i in range(0,6):
                if not isIssetTileByXY(offsetNeighbor((tile.x,tile.y),i)):
                    kray.append(i)
            if len(kray):
                for i in kray:
                    i1, i2 = getPointNumbers(i)
                    point1, point2 = tile.coords[i1], tile.coords[i2]
                    line = ((int(round(point1[0]*10000)), int(round(point1[1]*10000))), (int(round(point2[0]*10000)), int(round(point2[1]*10000))))
                    if line == ((1774, -698000), (1774, -697000)):
                        print ("FUCK")
                        print (tile.id)
                        print (tile.x, tile.y)
                        for i in range(0,6):
                            n = offsetNeighbor((tile.x,tile.y),i)
                            print(i,n,isIssetTileByXY(n))
                        quit()

                    if not line in lines:
                        lines.append(line)		
            counter += 1
            # if interactiveMode:
                # printProgress(counter,tilesLength,"get borders: ")

    linesLength = len(lines)
    tiles = None

    def getLeftAndRightIds(line):
        left = -1
        right = -1
        for i in range(linesLength):
            currentLine = lines[i]
            if currentLine == line:
                continue
            if pointEquals(line[0],currentLine[0]) or pointEquals(line[0],currentLine[1]):
                left = i                
            if pointEquals(line[1],currentLine[0]) or pointEquals(line[1],currentLine[1]):
                right = i

            if left > 0 and right > 0:
                break
        return (left, right)

    if interactiveMode:
            print ("{} lines found".format(linesLength))


    conturs = []
    linesAdded = []

    counter = 0
    def addLine(i, counter):
        if interactiveMode:
            printProgress(counter,linesLength,"adding lines: ")

        if i in linesAdded:
            if len(linesAdded) == linesLength:
                return -1
            for j in range(0,linesLength):
                if not j in linesAdded:
                    return j
        line = lines[i]
        linesAdded.append(i)
        left, right = getLeftAndRightIds(line)
        
        if (left < 0) and (right < 0):
            if interactiveMode:    
                print ("Error, line have no neighbors")
                print (line)
                p1, p2 = line
                p1 = (float(p1[0])/10000,float(p1[1])/10000)
                p2 = (float(p2[0])/10000,float(p2[1])/10000)
                line = (p1, p2)
                print (line)
            quit()
        elif (left < 0) or (right < 0):
            if interactiveMode:
                print('                                     ')
                print('                                     ')
                print('                                     ')
                print ("Error, line have only one neighbor")
                print (line)
                p1, p2 = line
                p1 = (float(p1[0])/10000,float(p1[1])/10000)
                p2 = (float(p2[0])/10000,float(p2[1])/10000)
                line = (p1, p2)
                print (line)
            quit()

        for contur in conturs:
            if lines[right] in contur:
                contur.append(line)
                return left

        contur = []
        contur.append(line)
        conturs.append(contur)
        return left

    n = 0
    if interactiveMode:
        printProgress(0,len(lines)+1,"adding lines: ")
    while n >= 0:
        counter += 1
        n = addLine(n, counter)

    if interactiveMode:
        print("Finded {} conturs".format(len(conturs)))

    return conturs

paths = []
for x in range(-15,15):
    for y in range(-15,15):
        conturs = implodeTiles(x*R,y*R)

        if len(conturs):
            for i in range(len(conturs)):
                for j in range(len(conturs[i])):
                    p1, p2 = conturs[i][j]
                    conturs[i][j] = (float(p1[0])/10000,float(p1[1])/10000)
            paths.append(conturs)

f = open(path+'/all-lands.json', 'w')
f.write(json.dumps(paths))

if interactiveMode:
        print("conturs writed to all-lands.json")