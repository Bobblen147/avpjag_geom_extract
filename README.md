# Alien versus Predator Jaguar Geometry Extraction PHP Helper Script
This PHP script will read a binary dump of an Alien vs Predator jaguar level map and generate a text file of the map documented as a simple list of single letter codes. 
This text file can be read by the avpjag_mapbuilder.py script to generate a 3d mesh of the level in Blender.

This script is very bare bones, and has had little testing. Use at your own risk.

## Credits
This script is based on reverse engineering of Alien versus Predator by Rich Whitehouse, as well as my own research on the map format performed using the BigPEmu Jaguar emulator and Noesis live debugger also by Rich Whitehouse
(https://www.richwhitehouse.com/jaguar/)


## Usage
The script almost works as is, but needs to be modified a little to suit your particular setup.

The $type variable at the top of the script can only be set to 'textfile' so can be left alone.

The $filename variable at the top of the script should be set to the name of the file you're interested in. This should be a binary dump of an Avp Jag level map.

This line '$bin = fopen("D:\\Maps\\".$filename, "r");' should be modified to reflect the location of the file you're interested in. Ideally that would be a variable too, but isn't yet.

Binary dumps of avp map data can be created using BigPEmu Debug version combined with Noesis Debugger. 
Start a game on the level you want, and use Noesis to dump memory from offset 0x4FC00. The amount of data to dump can be worked out by looking at the header just above 0x4FC00.
For all the maps I've looked at so far, it's 64 x 64 x 8 = 32768 (in decimal).

The $length and $width variables in the script are also defaulted to 64 x 64 (again in decimal) but can be altered if the map size is different.

example usage: php avpjag_mapreader.php > sublevel3.txt

## Additional Notes
Avp maps are stored as a 64 x 64 grid of cubes. Each cube is described by 8 bytes. 6 bytes to describe each face of the cube and 2 flags. 
The co-ordinate system for the grid starts at 0,0 in the top left of the level, and the data is stored as rows running from left to right. 
IE Sector 0,0 ... sector 63,0 then sector 0,1 ... sector 63,1 all the way through to sector 63,63


This script reads these bytes and produces text codes in the following format

aabbcdefgh


aa = x co-ordinate of sector

bb = y co-ordinate of sector

c = left hand wall definition (assuming the player is facing 'north' towards the top of the grid)

d = front wall definition

e = right hand wall definition

f = back wall definition

g = ceiling definition

h = floor definition

each face can be defined as any of the following characters

L standard left hand wall

F standard front wall

R standard right hand wall

B standard back wall

p pillars/wall struts on both sides

l pillar/wall strut on left side only

r pillar/wall strut on right side only

d door

x open space

C ceiling

F floor

