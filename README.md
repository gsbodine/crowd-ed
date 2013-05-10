Crowd-Ed
========
A crowd-sourcing plugin for Omeka

Crowd-Ed is still in development, but is currently functional -- at least for us :). It is built on the [Twitter Bootstrap](http://twitter.github.com/bootstrap) grid and UI framework and therefore requires the [Omeka Bootstrap theme](http://github.com/gsbodine/omeka-bootstrap). Also, since **we've moved to Omeka 2.0**, Crowd-Ed requires the [Omeka Entities plugin](http://github.com/gsbodine/plugin-Entities) that re-introduces some of the user/entity functionality that was dropped from Omeka 2.0. See notes below for more info on that.

In order to make external deadlines, Crowd-Ed's development recently has been focused more on the pilot project and less on the generically available plugin. That being said, all of the code is still here (though branched out), and **you can see the plugin in action now at [The Martha Berry Digital Archive](https://mbda.berry.edu)**
***
A few other things to note, if you're interested in keeping up with this or trying it out: 
* There are a few things still being worked out regarding what functionality is included in Crowd-Ed and what will be handled outside Crowd-Ed in other plug-ins and whether they'll be required or not:
** The [Omeka Entities plugin](http://github.com/gsbodine/plugin-Entities) is likely to remain a required plug-in for Crowd-Ed because it allows us to save information tied to users and what they've crowd-edited.
** There is a feature within Crowd-Ed that allows for full names (including titles and suffixes) to be captured in separate fields for arbitrary person-related metadata (including Dublin Core) in order to assist with searching, sorting, etc. *This may in fact become a separate and *optional* plugin.*
** I have tried to implement the omeka/plugin-GuestUser for much of the underlying user-related functionality
 
Further updates at https://github.com/gsbodine/crowd-ed/wiki/Getting-Started-with-Crowd-Ed (in case I forget to update the README as often as I should).
