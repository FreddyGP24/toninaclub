@php($selected = old('amenities', isset($room) ? ($room->amenities ?? []) : []))
<div class="form-grid">
<label>Número<input name="room_number" value="{{ old('room_number',$room->room_number ?? '') }}" required></label>
<label>Tipo<select name="type">@foreach(['Individual','Doble','Matrimonial','Suite','Familiar'] as $type)<option @selected(old('type',$room->type ?? '')===$type)>{{ $type }}</option>@endforeach</select></label>
<label>Capacidad<input type="number" name="capacity" min="1" max="20" value="{{ old('capacity',$room->capacity ?? 1) }}" required></label>
<label>Precio por noche<input type="number" step="0.01" min="1" name="price_per_night" value="{{ old('price_per_night',$room->price_per_night ?? '') }}" required></label>
<label class="wide">Descripción<textarea name="description">{{ old('description',$room->description ?? '') }}</textarea></label>
<fieldset class="wide"><legend>Comodidades</legend>
@foreach(['Baño privado','Televisión','Wi-Fi','Aire acondicionado','Minibar','Balcón'] as $item)
<label class="check"><input type="checkbox" name="amenities[]" value="{{ $item }}" @checked(in_array($item,$selected,true))> {{ $item }}</label>
@endforeach
</fieldset>
<input type="hidden" name="active" value="0">
<label class="check wide"><input type="checkbox" name="active" value="1" @checked((bool)old('active',$room->active ?? true))> Habitación activa</label>
</div>
