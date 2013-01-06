Crowd-Ed
========
A crowd-sourcing plugin for Omeka

Crowd-Ed is still in the early stages of development, but is currently functional -- at least for us :). It is built on the [Twitter Bootstrap](http://twitter.github.com/bootstrap) grid and UI framework and therefore requires the [Omeka Bootstrap theme](http://github.com/gsbodine/omeka-bootstrap). Also, since **we're moving to Omeka 2.0's architecture**, Crowd-Ed is going to require the [Omeka Entities plugin](http://github.com/gsbodine/plugin-Entities) that re-introduces some of the user/entity functionality that was dropped from Omeka 2.0. See notes below for more info on that.

**The actual release date of the first complete version is expected to be early April 2013.**
***
A few other things to note, if you're interested in keeping up with this or trying it out: 
* *It is now being developed based on Omeka 2.0* (in Release Candidacy as this is written) -- there is an imcomplete but functional version in an Omeka 1.5 branch (and tag) if you're interested in starting from there. 
* There are a few things still being worked out regarding what functionality is included in Crowd-Ed and what will be handled outside Crowd-Ed in other plug-ins and whether they'll be required or not:
** The [Omeka Entities plugin](http://github.com/gsbodine/plugin-Entities) is likely to remain a required plug-in for Crowd-Ed because it allows us to save information tied to users and what they've crowd-edited.
** There is a feature within Crowd-Ed that allows for full names (including titles and suffixes) to be captured in separate fields for arbitrary person-related metadata (including Dublin Core) in order to assist with searching, sorting, etc. *This may in fact become a separate and *optional* plugin.* More on that later...
 
Further updates at https://github.com/gsbodine/crowd-ed/wiki/Getting-Started-with-Crowd-Ed (in case I forget to update the README as often as I should).
