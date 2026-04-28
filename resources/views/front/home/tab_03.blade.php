<form action="" method="POST" id="deliveryForm" name="deliveryForm">
    @csrf
        <input type="hidden" name="order_type" value="Delivery">
        <div class="basket-page__content__notes">
            <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
        </div>

        <div class="basket-page__content__delivery-content" style="border-top-left-radius: 0px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                <path
                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z">
                </path>
                <path
                    d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z">
                </path>
            </svg>
            <select class="form-select" aria-label="Default select example" name="ready_time">
                <option selected>Ready time</option>
                <option value="1">10:00</option>
                <option value="2">11:00</option>
                <option value="3">12:00</option>
            </select>
        </div>

        <div class="basket-page__content__delivery-content" >
            <textarea class="form-control" id="address" name="address" placeholder="Enter address">address</textarea>
        </div>
        
        <div class="form-group mb-2">
            <input type="text" id="delivery_name" class="form-control" placeholder="Name" name="delivery_name">
        </div>
        <div class="form-group mb-2">
            <input type="phone" id="delivery_phone" class="form-control" placeholder="Phone" name="delivery_phone">
        </div>
        <div class="form-group mb-2">
            <input type="email" id="delivery_email" class="form-control" placeholder="Email" name="delivery_email">
        </div>

        <div class="basket-order-button-container">
            <button type="submit" class="btn btn--brand basket-page__content__order-btn">Order</button>
        </div>
    </form>