@extends('client.layouts.app')

@section('title', 'ط§ظ„طھظ‚ظٹظٹظ…ط§طھ ظˆط§ظ„ظ…ط±ط§ط¬ط¹ط§طھ')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ط§ظ„طھظ‚ظٹظٹظ…ط§طھ ظˆط§ظ„ظ…ط±ط§ط¬ط¹ط§طھ</h1>
            <p class="text-gray-600">ط´ط§ط±ظƒ طھط¬ط±ط¨طھظƒ ظ…ط¹ ط§ظ„ظ…ط´ط§ط±ظٹط¹ ظˆط§ظ„ظ…ظ†ط´ط¢طھ</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 space-x-reverse" aria-label="Tabs">
                    <button onclick="showTab('my-reviews')" id="my-reviews-tab" 
                            class="tab-button active py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap">
                        <i class="fas fa-star ml-2"></i>
                        طھظ‚ظٹظٹظ…ط§طھظٹ
                    </button>
                    <button onclick="showTab('add-review')" id="add-review-tab" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm whitespace-nowrap text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-plus ml-2"></i>
                        ط¥ط¶ط§ظپط© طھظ‚ظٹظٹظ…
                    </button>
                </nav>
            </div>
        </div>

        <!-- My Reviews Tab -->
        <div id="my-reviews-content" class="tab-content">
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $review->reviewable->name ?? 'ط؛ظٹط± ظ…ط­ط¯ط¯' }}
                                        </h3>
                                        <div class="flex items-center mr-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                            <span class="text-sm text-gray-600 mr-2">({{ $review->rating }}/5)</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3">{{ $review->comment }}</p>
                                    
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span class="mr-4">
                                            <i class="fas fa-calendar ml-1"></i>
                                            {{ $review->created_at->format('Y-m-d') }}
                                        </span>
                                        <span class="mr-4">
                                            <i class="fas fa-tag ml-1"></i>
                                            {{ $review->reviewable_type == 'App\Models\Product' ? 'ظ…ط´ط±ظˆط¹' : 'ظ…ظ†ط´ط£ط©' }}
                                        </span>
                                        @if($review->is_verified)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check ml-1"></i> ظ…ظˆط«ظ‚
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <button onclick="editReview({{ $review->id }})" 
                                            class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteReview({{ $review->id }})" 
                                            class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-star text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">ظ„ط§ طھظˆط¬ط¯ طھظ‚ظٹظٹظ…ط§طھ</h3>
                    <p class="text-gray-600 mb-6">ط§ط¨ط¯ط£ ط¨طھظ‚ظٹظٹظ… ط§ظ„ظ…ط´ط§ط±ظٹط¹ ظˆط§ظ„ظ…ظ†ط´ط¢طھ ط§ظ„طھظٹ طھط¹ط§ظ…ظ„طھ ظ…ط¹ظ‡ط§</p>
                    <button onclick="showTab('add-review')" 
                            class="bg-primary-600 text-white px-6 py-3 rounded-md hover:bg-primary-700 transition-colors">
                        ط¥ط¶ط§ظپط© طھظ‚ظٹظٹظ… ط¬ط¯ظٹط¯
                    </button>
                </div>
            @endif
        </div>

        <!-- Add Review Tab -->
        <div id="add-review-content" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">ط¥ط¶ط§ظپط© طھظ‚ظٹظٹظ… ط¬ط¯ظٹط¯</h2>
                
                <form method="POST" action="{{ route('client.reviews.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Review Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ظ†ظˆط¹ ط§ظ„طھظ‚ظٹظٹظ…</label>
                        <div class="flex space-x-4 space-x-reverse">
                            <label class="flex items-center">
                                <input type="radio" name="reviewable_type" value="product" 
                                       class="form-radio text-primary-600" checked>
                                <span class="mr-2">ظ…ط´ط±ظˆط¹</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="reviewable_type" value="facility" 
                                       class="form-radio text-primary-600">
                                <span class="mr-2">ظ…ظ†ط´ط£ط©</span>
                            </label>
                        </div>
                    </div>

                    <!-- Select Item -->
                    <div>
                        <label for="reviewable_id" class="block text-sm font-medium text-gray-700 mb-2">ط§ط®طھط± ط§ظ„ط¹ظ†طµط±</label>
                        <select id="reviewable_id" name="reviewable_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                            <option value="">ط§ط®طھط± ط§ظ„ظ…ط´ط±ظˆط¹ ط£ظˆ ط§ظ„ظ…ظ†ط´ط£ط©</option>
                        </select>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„طھظ‚ظٹظٹظ…</label>
                        <div class="flex items-center space-x-1 space-x-reverse" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" 
                                        class="star-button text-2xl text-gray-300 hover:text-yellow-400" 
                                        data-rating="{{ $i }}">
                                    <i class="far fa-star"></i>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" id="rating-input" value="0" required>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„طھط¹ظ„ظٹظ‚</label>
                        <textarea id="comment" name="comment" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" 
                                  placeholder="ط§ظƒطھط¨ طھط¹ظ„ظٹظ‚ظƒ ظ‡ظ†ط§..." required></textarea>
                    </div>

                    <!-- Pros and Cons -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pros" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط¥ظٹط¬ط§ط¨ظٹط§طھ</label>
                            <textarea id="pros" name="pros" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" 
                                      placeholder="ظ…ط§ ط£ط¹ط¬ط¨ظƒ ظپظٹ ظ‡ط°ط§ ط§ظ„ظ…ط´ط±ظˆط¹/ط§ظ„ظ…ظ†ط´ط£ط©طں"></textarea>
                        </div>
                        <div>
                            <label for="cons" class="block text-sm font-medium text-gray-700 mb-2">ط§ظ„ط³ظ„ط¨ظٹط§طھ</label>
                            <textarea id="cons" name="cons" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500" 
                                      placeholder="ظ…ط§ ظ„ظ… ظٹط¹ط¬ط¨ظƒ ظپظٹ ظ‡ط°ط§ ط§ظ„ظ…ط´ط±ظˆط¹/ط§ظ„ظ…ظ†ط´ط£ط©طں"></textarea>
                        </div>
                    </div>

                    <!-- Anonymous Review -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" 
                               class="form-checkbox text-primary-600">
                        <label for="is_anonymous" class="mr-2 text-sm text-gray-700">
                            طھظ‚ظٹظٹظ… ظ…ط¬ظ‡ظˆظ„
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse">
                        <button type="button" onclick="showTab('my-reviews')" 
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            ط¥ظ„ط؛ط§ط،
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            ط¥ط¶ط§ظپط© ط§ظ„طھظ‚ظٹظٹظ…
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentRating = 0;

    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating-input').value = rating;
        
        // Update star display
        document.querySelectorAll('.star-button').forEach((star, index) => {
            if (index < rating) {
                star.innerHTML = '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                star.innerHTML = '<i class="far fa-star text-gray-300"></i>';
            }
        });
    }

    function editReview(reviewId) {
        // Implementation for editing review
        alert('طھط¹ط¯ظٹظ„ ط§ظ„طھظ‚ظٹظٹظ… - ' + reviewId);
    }

    function deleteReview(reviewId) {
        if (confirm('ظ‡ظ„ ط£ظ†طھ ظ…طھط£ظƒط¯ ظ…ظ† ط­ط°ظپ ظ‡ط°ط§ ط§ظ„طھظ‚ظٹظٹظ…طں')) {
            fetch(`/client/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    // Load items based on reviewable type
    document.querySelectorAll('input[name="reviewable_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const type = this.value;
            const select = document.getElementById('reviewable_id');
            
            // Clear existing options
            select.innerHTML = '<option value="">ط§ط®طھط± ط§ظ„ظ…ط´ط±ظˆط¹ ط£ظˆ ط§ظ„ظ…ظ†ط´ط£ط©</option>';
            
            // Load items based on type
            fetch(`/client/reviews/items/${type}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>
@endpush

@push('styles')
<style>
.tab-button.active {
    border-color: #3b82f6;
    color: #3b82f6;
}

.bg-primary-600 {
    background-color: #2563eb;
}

.hover\:bg-primary-700:hover {
    background-color: #1d4ed8;
}

.focus\:ring-primary-500:focus {
    --tw-ring-color: #3b82f6;
}

.star-button:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}
</style>
@endpush

