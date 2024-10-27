<div class="mb-3">
    <label for="{{$name}}" class="form-label">{{$text}}</label>
    <input id="{{$name}}" type="{{$type}}" class="form-control @error($name) is-invalid @enderror" name="{{$name}}" placeholder="{{$placeholder}}" value="{{old($name)}}">
    @error($name)
    <div class="invalid-feedback">{{$message}}</div>
    @enderror
</div>
