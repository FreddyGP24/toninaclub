@extends('layouts.app')
@section('title', 'Mapa de hoteles')
@section('content')
<h1>Mapa de hoteles</h1>
@if(!config('services.google_maps.browser_key'))
    <div class="alert error">Falta configurar GOOGLE_MAPS_BROWSER_KEY.</div>
@endif
<div id="hotels-map" class="map large"></div>
@endsection

@push('scripts')
<script>
window.initHotelsMap = async function () {
    const hotels = @json($hotels);
    const {Map, InfoWindow} = await google.maps.importLibrary('maps');
    const {AdvancedMarkerElement} = await google.maps.importLibrary('marker');
    const map = new Map(document.getElementById('hotels-map'), {
        center:{lat:23.6345,lng:-102.5528}, zoom:5,
        mapId:@json(config('services.google_maps.map_id'))
    });
    const info = new InfoWindow();
    const bounds = new google.maps.LatLngBounds();

    hotels.forEach(hotel => {
        const position = {lat:Number(hotel.latitude),lng:Number(hotel.longitude)};
        const marker = new AdvancedMarkerElement({map,position,title:hotel.name});
        bounds.extend(position);
        marker.addListener('click', () => {
            const box=document.createElement('div');
            const h=document.createElement('h3'); h.textContent=hotel.name;
            const p=document.createElement('p'); p.textContent=hotel.address ?? '';
            const a=document.createElement('a'); a.href=hotel.url; a.textContent='Ver hotel';
            box.append(h,p,a); info.setContent(box); info.open({map,anchor:marker});
        });
    });

    if(hotels.length===1){map.setCenter({lat:Number(hotels[0].latitude),lng:Number(hotels[0].longitude)});map.setZoom(15);}
    if(hotels.length>1){map.fitBounds(bounds,70);}
};
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.browser_key') }}&loading=async&callback=initHotelsMap&language=es&region=MX"></script>
@endpush
