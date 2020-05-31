---
# Inkscape 0.93 Cookie Cutter Extension
---
I decided to make a single SVG to create all my icons from.
This is the **base.svg** file. Which all 3 files need to be
moved to your **~/.config/inkscape/extensions/** directory.
```
uso_cookie_cutter_procedural.inx
uso_cookie_cutter.py
base.svg
```
When you start Inkscape go to.
```
File->New From Template->Userspace Cookie Cutter.
```
Then select the icon of your choice.

I edit the text to match name of program I want to link to 
with the **slider carousel**. I may need to resize depending
on the program name.

You can recenter text:
```
ctrl+shift+a
```
Then select center on vertical axis.
To finalise the icon I select all the objects and group:

```
ctrl+a ctrl+g
```

then I simplfy the icon with
```
ctrl+l
```

This makes the icon much smaller,around 10k per icon so I dont have
to keep up with more than one icon and its kept in the inkscape
extnension directory. 

