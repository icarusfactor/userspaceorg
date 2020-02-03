#! /usr/bin/python2

# Written by icarusfactor
# V.0.5

import simplestyle
import inkex, sys, re, os
from lxml import etree


class C(inkex.Effect):
  def __init__(self):
    inkex.Effect.__init__(self)
    self.OptionParser.add_option("-c", "--cat",  action="store", type="int",    dest="category",  default="1", help="List Item")
    #self.OptionParser.add_option("-s", "--size",  action="store", type="int",    dest="font_size",  default="14", help="Font Size")
    #self.OptionParser.add_option("-t", "--title",   action="store", type="string", dest="icon_title",   default="TEST TEXT",   help="Icon Title")

  def effect(self):
    width  = 150
    height = 150
    unit   = "px"
 
    
    path = os.path.dirname(os.path.realpath(__file__))
    self.document = etree.parse(os.path.join(path, "base.svg"))
    svg = self.document.getroot()
    #root.set("id", "USOICON")
    #svg.set("width",  str(width) + unit)
    #svg.set("height", str(height) + unit)
    #svg.set("viewBox", "0 0 " + str(width) + " " + str(height) )

    #Remove all layers except one selected in category
    if self.options.category != 1:
       layer = self.document.xpath( '//*[@inkscape:label="icon_office"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 2:
       layer = self.document.xpath( '//*[@inkscape:label="icon_games"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 3:  
       layer = self.document.xpath( '//*[@inkscape:label="icon_system"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 4:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_utilities"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 5:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_multimedia"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 6:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_graphics"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 7:    
       layer = self.document.xpath( '//*[@inkscape:label="icon_development"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 8:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_education"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis
    if self.options.category != 9:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_internet"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis   
    if self.options.category != 10:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_science"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis  
    if self.options.category != 11:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_server"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis  
    if self.options.category != 12:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_wm"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis        
    if self.options.category != 13:   
       layer = self.document.xpath( '//*[@inkscape:label="icon_de"]', namespaces=inkex.NSS)
       if layer: svg.remove(layer[0]) # remove if it existis     
       
 
    namedview = svg.find(inkex.addNS('namedview', 'sodipodi'))
    #if namedview is None:
    #   namedview = inkex.etree.SubElement( root, inkex.addNS('namedview', 'sodipodi') );

    namedview.set(inkex.addNS('document-units', 'inkscape'), unit)

    # Until units are supported in 'cx', etc.
    namedview.set(inkex.addNS('zoom', 'inkscape'), str(512.0/self.uutounit( width,  'px' )) )
    namedview.set(inkex.addNS('cx',   'inkscape'), str(self.uutounit( width,  'px' )/2.0 ) )
    namedview.set(inkex.addNS('cy',   'inkscape'), str(self.uutounit( height, 'px' )/2.0 ) )


c = C()
c.affect()    
