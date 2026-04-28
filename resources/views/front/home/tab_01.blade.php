    <input type="hidden" name="order_type" value="Dinein">

    <div class="basket-page__content__notes">
        <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
    </div>

    <div class="basket-page__content__delivery-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
            </path>
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
            </path>
        </svg>

        <select class="form-select" aria-label="Default select example" name="ready_time">
            <option selected>Ready time</option>
            <option value="1">10:00</option>
            <option value="2">11:00</option>
            <option value="3">12:00</option>
        </select>       
        
        <select name="table_number" id="table_number" class="form-control mt-2 mb-2">
            <option value="">Select a Table</option>
            @if($seats->isNotEmpty())
                @foreach ($seats as $value)
                    @if($value->area_id == NULL)
                        <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                    @elseif($value->area_id == '')
                        <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                    @endif
                @endforeach
            @endif
        </select>  
        
        {{-- {{ $seats }} --}}
    </div>

    <button type="submit" class="btn btn-primary">Order</button>
</form>