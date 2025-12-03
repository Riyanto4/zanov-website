{{-- resources/views/components/rating-form.blade.php --}}
<div class="mt-6 pt-6 border-t border-gray-800">
    <h3 class="text-lg font-medium text-accent mb-4">Rate this Product</h3>
    
    @if($existingRating)
        <div class="mb-4 p-3 bg-gray-900 border border-gray-700">
            <div class="flex items-center mb-2">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-xl {{ $i <= $existingRating->rating ? 'text-yellow-400' : 'text-gray-600' }}">
                            ★
                        </span>
                    @endfor
                </div>
                <span class="ml-2 text-sm text-gray-400">
                    You rated {{ $existingRating->rating }} stars
                </span>
            </div>
            @if($existingRating->comment)
                <p class="text-gray-300 text-sm">"{{ $existingRating->comment }}"</p>
            @endif
        </div>
    @else
        <form action="{{ route('ratings.store', $transaction) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Star Rating - Fixed: Left to Right -->
            <div class="flex items-center space-x-1">
                @for($i = 1; $i <= 5; $i++)
                    <input type="radio" id="star{{ $i }}-{{ $product->id }}" name="rating" 
                           value="{{ $i }}" 
                           class="hidden"
                           {{ old('rating') == $i ? 'checked' : '' }}>
                    <label for="star{{ $i }}-{{ $product->id }}" 
                           class="cursor-pointer text-2xl text-gray-600 hover:text-yellow-400 transition duration-300 star-label"
                           data-value="{{ $i }}">
                        ★
                    </label>
                @endfor
                <span class="ml-2 text-sm text-gray-400">
                    (Select your rating)
                </span>
            </div>
            
            <!-- Comment -->
            <div>
                <label for="comment-{{ $product->id }}" class="block text-sm font-medium text-gray-400 mb-1">
                    Your Review (Optional)
                </label>
                <textarea id="comment-{{ $product->id }}" name="comment" rows="3"
                          class="w-full px-3 py-2 bg-gray-900 border border-gray-800 rounded-none text-gray-300 focus:ring-2 focus:ring-accent focus:border-accent placeholder-gray-500"
                          placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-accent text-sm font-medium rounded-none shadow-sm text-accent hover:bg-accent hover:text-primary transition duration-300">
                    Submit Rating
                </button>
            </div>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating interaction
    document.querySelectorAll('.star-label').forEach(label => {
        // Click event
        label.addEventListener('click', function() {
            const value = parseInt(this.getAttribute('data-value'));
            const form = this.closest('form');
            const starLabels = form.querySelectorAll('.star-label');
            
            // Update all stars
            starLabels.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.remove('text-gray-600');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-600');
                }
            });
            
            // Check the corresponding radio button
            const radio = form.querySelector(`#star${value}-${form.querySelector('input[name="product_id"]').value}`);
            if (radio) {
                radio.checked = true;
            }
        });
        
        // Hover events
        label.addEventListener('mouseenter', function() {
            const value = parseInt(this.getAttribute('data-value'));
            const form = this.closest('form');
            const starLabels = form.querySelectorAll('.star-label');
            
            starLabels.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.add('text-yellow-300');
                }
            });
        });
        
        label.addEventListener('mouseleave', function() {
            const form = this.closest('form');
            const starLabels = form.querySelectorAll('.star-label');
            const checkedRadio = form.querySelector('input[name="rating"]:checked');
            
            starLabels.forEach(star => {
                star.classList.remove('text-yellow-300');
            });
            
            // Restore state based on selected rating
            if (checkedRadio) {
                const value = parseInt(checkedRadio.value);
                starLabels.forEach(star => {
                    const starValue = parseInt(star.getAttribute('data-value'));
                    if (starValue <= value) {
                        star.classList.add('text-yellow-400');
                    }
                });
            }
        });
    });
    
    // Initialize stars based on existing value
    document.querySelectorAll('form').forEach(form => {
        const checkedRadio = form.querySelector('input[name="rating"]:checked');
        if (checkedRadio) {
            const value = parseInt(checkedRadio.value);
            const starLabels = form.querySelectorAll('.star-label');
            
            starLabels.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-value'));
                if (starValue <= value) {
                    star.classList.remove('text-gray-600');
                    star.classList.add('text-yellow-400');
                }
            });
        }
    });
});
</script>