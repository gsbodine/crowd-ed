<?php
if (!empty($formActionUri)):
    $formAttributes['action'] = $formActionUri;
else:
    $formAttributes['action'] = url(array('controller'=>'items',
                                          'action'=>'browse'));
endif;
$formAttributes['method'] = 'GET';

$radius = '';
$address = '';
$currentLat = '';
$currentLng = '';

?>

<form id="map-search-form" <?php echo tag_attributes($formAttributes); ?>>

<div class="span12">
    <hr />
    <p class="lead"><i class="icon-map-marker"></i> Search for Document Locations on the Map</p>
</div>    
  
<div class="span4">
    <div class="row">
        <div class="span2">
            <?php echo $this->formLabel('geolocation-address', __('Location')); ?>
            <?php echo $this->formText('geolocation-address',  $address, array('name'=>'geolocation-address','placeholder' => 'e.g. City, State','id'=>'geolocation-address','class'=>'textinput span2')); ?>
            <?php echo $this->formHidden('geolocation-latitude', $currentLat, array('name'=>'geolocation-latitude','id'=>'geolocation-latitude')); ?>
            <?php echo $this->formHidden('geolocation-longitude', $currentLng, array('name'=>'geolocation-longitude','id'=>'geolocation-longitude')); ?>
            <?php echo $this->formHidden('geolocation-radius', $radius, array('name'=>'geolocation-radius','id'=>'geolocation-radius')); ?>
        </div>
        <div class="span2">
            <?php echo $this->formLabel('geolocation-radius', __('Radius (in miles)')); ?>
            <?php echo $this->formText('geolocation-radius', $radius, array('name'=>'geolocation-radius','id'=>'geolocation-radius','class'=>'textinput span1')); ?>
    
        </div>
    </div>
</div>
    
<div class="span3">
    <div id="search-keywords" class="field">
        <?php echo $this->formLabel('keyword-search', __('Search for Keywords')); ?>
        <div class="inputs">
        <?php
            echo $this->formText(
                'search',
                @$_REQUEST['search'],
                array('id' => 'keyword-search', 'class' => 'span3')
            );
            
        ?>
        </div>
    </div>
</div>
<div class="span3">
    <div class="field">
        <?php echo $this->formLabel('collection-search', __('Search By Collection')); ?>
        <div class="inputs">
        <?php
            echo $this->formSelect(
                'collection',
                @$_REQUEST['collection'],
                array('id' => 'collection-search','class'=>'span3'),
                get_table_options('Collection')
            );
        ?>
        </div>
    </div>
</div>
<div class="span2">
    <div class="field">
        <?php echo $this->formLabel('tag-search', __('Search By Tags')); ?>
        <div class="inputs">
        <?php
            echo $this->formText('tags', @$_REQUEST['tags'],
                array('class' => 'span2', 'id' => 'tag-search')
            );
        ?>
        </div>
    </div>
</div>
<?php
    $request = Zend_Controller_Front::getInstance()->getRequest();

    $address = trim($request->getParam('geolocation-address'));
    $currentLat = trim($request->getParam('geolocation-latitude'));
    $currentLng = trim($request->getParam('geolocation-longitude'));
    $radius = trim($request->getParam('geolocation-radius'));

    if (empty($radius)) {
        $radius = 10; // 10 miles
    }
?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#submit-search-maps').click(function(event) {

        var address = jQuery('#geolocation-address').val();
        if (jQuery.trim(address).length > 0) {
            var geocoder = new google.maps.Geocoder();	        
            geocoder.geocode({'address': address}, function(results, status) {
                // If the point was found, then put the marker on that spot
                    if (status == google.maps.GeocoderStatus.OK) {
                            var gLatLng = results[0].geometry.location;
                    // Set the latitude and longitude hidden inputs
                    jQuery('#geolocation-latitude').val(gLatLng.lat());
                    jQuery('#geolocation-longitude').val(gLatLng.lng());
                    jQuery('#map-search-form').submit();
                    } else {
                            // If no point was found, give us an alert
                        alert('Error: "' + address + '" was not found!');
                    }
            });

            event.stopImmediatePropagation();
            return false;
        }                
        });
    });
</script>

<div class="span12">
    <button type="submit" class="submit btn btn-success" name="submit_search" id="submit-search-maps" value="<?php echo __('Search'); ?>"><i class="icon-map-marker"></i> Submit</button>
</div>
</form>

<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>
