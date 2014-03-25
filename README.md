citibike-panic
==============

Panic Status Board widget to indicate how many NYC citibikes are available nearby. 

#usage#
  * Install to a php server that your Status Board can access.
  * edit the stations list in the index.php file to the stations nearest you
    * you'll probably need to wade through this json response to find the ones you want
    ``http://appservices.citibikenyc.com/data2/stations.php``
  * In the Status Board app, drag in a new graph widget and point it to the appropriate URL.
  * ride like the wind.
