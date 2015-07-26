<div class="list-group">
    <a class="list-group-item {{ Request::is('settings/company') ? 'active' : '' }} " href="{{ route('settings.company.index') }}"> Company</a>
    <a class="list-group-item {{ Request::is('settings/invoice') ? 'active' : '' }}" href="{{ route('settings.invoice.index') }}"> Invoice</a>
    <a class="list-group-item {{ Request::is('settings/tax') ? 'active' : '' }}" href="{{ route('settings.tax.index') }}"> Tax</a>
    <a class="list-group-item {{ Request::is('settings/templates/*') ? 'active' : '' }}" href="{{ route('settings.templates.show', 'invoice') }}"> Templates</a>
    <a class="list-group-item {{ Request::is('settings/number') ? 'active' : '' }}" href="{{ route('settings.number.index') }}"> Numbering</a>
    <a class="list-group-item {{ Request::is('settings/payment') ? 'active' : '' }}" href="{{ route('settings.payment.index') }}"> Payments Methods</a>
    <a class="list-group-item {{ Request::is('settings/currency') ? 'active' : '' }}" href="{{ route('settings.currency.index') }}"> Currency</a>
</div>