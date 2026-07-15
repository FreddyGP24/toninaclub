@php($selected = old('services', isset($hotel) ? ($hotel->services ?? []) : []))
<div class="form-grid">
<label>Nombre<input name="name" value="{{ old('name',$hotel->name ?? '') }}" required></label>
<label>Teléfono<input name="phone" value="{{ old('phone',$hotel->phone ?? '') }}" required></label>
<label>Correo<input type="email" name="email" value="{{ old('email',$hotel->email ?? '') }}"></label>
<label>Dirección<input name="address_line" value="{{ old('address_line',$hotel->address_line ?? '') }}" required></label>
<label>Ciudad<input name="city" value="{{ old('city',$hotel->city ?? '') }}" required></label>
<label>Estado<input name="state" value="{{ old('state',$hotel->state ?? '') }}" required></label>
<label>Código postal<input name="postal_code" value="{{ old('postal_code',$hotel->postal_code ?? '') }}"></label>
<label>País<input name="country" value="{{ old('country',$hotel->country ?? 'México') }}" required></label>
<label class="wide">Descripción<textarea name="description" rows="5">{{ old('description',$hotel->description ?? '') }}</textarea></label>
<fieldset class="wide"><legend>Servicios</legend>
@foreach(['Wi-Fi','Estacionamiento','Piscina','Restaurante','Aire acondicionado','Gimnasio'] as $service)
<label class="check"><input type="checkbox" name="services[]" value="{{ $service }}" @checked(in_array($service,$selected,true))> {{ $service }}</label>
@endforeach
</fieldset>
<input type="hidden" name="active" value="0">
<label class="check wide"><input type="checkbox" name="active" value="1" @checked((bool)old('active',$hotel->active ?? true))> Hotel activo</label>
</div>
