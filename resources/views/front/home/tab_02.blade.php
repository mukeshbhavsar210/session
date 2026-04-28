
<form action="" method="POST" id="takeawayForm" name="takeawayForm">
    @csrf
    <input type="hidden" name="order_type" value="Takeaway">

    <div class="basket-page__content__notes">
        <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
    </div>

    <div class="basket-page__content__delivery-content" >
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
    </div>

    <div class="form-group mb-2">
        <input type="text"class="form-control" placeholder="Name" name="takeaway_name">
    </div>

    <div class="form-group mb-2">
        <input type="phone" class="form-control" placeholder="Phone" name="takeaway_phone">
    </div>
    
    <div class="form-group mb-2">
        <input type="email" class="form-control" placeholder="Email" name="takeaway_email">
    </div>
    <button type="submit" class="btn btn-primary">Order 2</button>
</form>