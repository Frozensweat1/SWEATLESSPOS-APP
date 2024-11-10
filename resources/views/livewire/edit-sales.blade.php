<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor" id="basic-form">User form</h5>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="tab-content">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-name">Name</label>
                        <input class="form-control" id="basic-form-name" type="text" placeholder="Name" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-email">Email address</label>
                        <input class="form-control" id="basic-form-email" type="email"
                            placeholder="name@example.com" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-password">Password</label>
                        <input class="form-control" id="basic-form-password" type="password" placeholder="Password" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-dob">Confirm Password</label>
                        <input class="form-control" id="basic-form-dob" type="password" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-gender">User Role</label>
                        <select class="form-select" id="basic-form-gender" aria-label="Default select example">
                            <option selected="selected" disabled>Select user role</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <input class="form-control" type="file" />
                    </div>
                    <button class="btn btn-sm btn-falcon-default rounded-pill me-1 mb-1 float-end" type="button">
                        <span class="fas fa-save"></span>
                        Add User
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
