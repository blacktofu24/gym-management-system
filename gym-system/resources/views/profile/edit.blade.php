@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Edit Profile</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('member.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="text-primary mb-3">Account Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address (Cannot be changed)</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                        </div>

                        <hr class="my-4">
                        
                        <h5 class="text-primary mb-3">Personal Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $member->date_of_birth ?? '') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Home Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $member->address ?? '') }}</textarea>
                        </div>

                        <hr class="my-4">
                        
                        <h5 class="text-danger mb-3">Emergency Contact</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $member->emergency_contact_name ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone ?? '') }}">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Profile Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection