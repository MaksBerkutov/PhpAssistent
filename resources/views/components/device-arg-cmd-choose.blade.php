<div class="form-group">
    <label for="change_arg">{{$label}}</label>
    <input type="text" class="form-control @error($name) is-invalid @enderror" id="{{$name}}" name="{{$name}}"
           value="{{old($name,$old)}}" >
</div>
