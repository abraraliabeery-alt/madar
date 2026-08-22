@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">{{ __('إحالاتي') }}</h1>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('رابط الإحالة') }}</h2>
            <div class="flex items-center gap-2">
                <input type="text" id="referralLink" value="{{ $user->referralLink() }}" readonly class="w-full px-3 py-2 border rounded-md bg-gray-100 dark:bg-slate-700 text-sm">
                <button onclick="copyReferralLink()" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                    {{ __('نسخ') }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('إجمالي المسجلين') }}</p>
                <p class="text-2xl font-bold">{{ $totalReferred }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('المحولّين') }}</p>
                <p class="text-2xl font-bold">{{ $totalConverted }}</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('كود الإحالة') }}</p>
                <p class="text-2xl font-bold">{{ $user->referral_code }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-slate-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3">{{ __('المستخدم') }}</th>
                        <th class="px-4 py-3">{{ __('الحالة') }}</th>
                        <th class="px-4 py-3">{{ __('التاريخ') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $referral->referredUser?->name ?? '-' }}</td>
                            <td class="px-4 py-3 capitalize">{{ $referral->status }}</td>
                            <td class="px-4 py-3">{{ $referral->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">{{ __('لا توجد إحالات بعد') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $referrals->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const input = document.getElementById('referralLink');
    input.select();
    document.execCommand('copy');
    alert('{{ __('تم نسخ الرابط') }}');
}
</script>
@endsection
