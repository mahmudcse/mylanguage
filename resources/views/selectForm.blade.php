<option selected>No of times reads</option>

@if (count($options) > 0)
    @foreach ($options as $option)
        <option value="{{ $option->no_of_read }}">{{ $option->no_of_read }}</option>
    @endforeach
@else
    <p>No Options Found</p>
@endif

