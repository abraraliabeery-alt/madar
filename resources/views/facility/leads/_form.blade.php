<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">الاسم <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $lead->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">الجوال</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $lead->phone ?? '') }}" dir="ltr">
    </div>
    <div class="col-md-3">
        <label class="form-label">البريد</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? '') }}" dir="ltr">
    </div>

    <div class="col-md-3">
        <label class="form-label">المصدر</label>
        <select name="source" class="form-select">
            <option value="">—</option>
            @foreach(\App\Models\Lead::SOURCES as $key => $label)
                <option value="{{ $key }}" @selected(old('source', $lead->source ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">الاهتمام</label>
        <select name="interest_type" class="form-select">
            <option value="">—</option>
            <option value="buy" @selected(old('interest_type', $lead->interest_type ?? '') === 'buy')>شراء</option>
            <option value="rent" @selected(old('interest_type', $lead->interest_type ?? '') === 'rent')>إيجار</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">المرحلة</label>
        <select name="stage" class="form-select">
            @foreach(\App\Models\Lead::STAGES as $key => $label)
                <option value="{{ $key }}" @selected(old('stage', $lead->stage ?? 'new') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">الميزانية</label>
        <input type="number" name="budget" class="form-control" min="0" step="0.01" value="{{ old('budget', $lead->budget ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">العقار المهتم به</label>
        <select name="product_id" class="form-select">
            <option value="">—</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(old('product_id', $lead->product_id ?? '') == $product->id)>
                    {{ $product->getTranslatedTitle() ?: '#'.$product->id }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">الموظف المسؤول</label>
        <select name="assigned_to" class="form-select">
            <option value="">—</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" @selected(old('assigned_to', $lead->assigned_to ?? '') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">ملاحظات</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead->notes ?? '') }}</textarea>
    </div>
</div>
