  </div>
  </div>
  </div>
  <div class="modal-footer">

      <button type="button" wire:click="closeModal" class="btn btn-sm btn-falcon-danger rounded-pill me-1 mb-1 float-end"
          data-bs-dismiss="modal">Close</button>
      <!--[if BLOCK]><![endif]--><?php if($selected_id < 1): ?>
          <button type="button" wire:click.prevent="Store()"
              class="btn btn-sm btn-outline-primary rounded-pill me-1 d-flex align-items-center float-end"
              wire:loading.attr="disabled" wire:loading.class="disabled" wire:loading.class.remove="btn-outline-primary"
              wire:loading.class.add="btn-primary">
              <!-- Spinner -->
              <span wire:loading wire:target="Store" class="spinner-border spinner-border-sm me-2" role="status"
                  aria-hidden="true"></span>
              <!-- Icon for smaller screens -->
              <span class="d-none d-md-inline">
                  <i class="fas fa-save"></i>
                  <span wire:loading.remove wire:target="Store"> Save</span>
              </span>
              <span wire:loading wire:target="Store"> Saving...</span>
              <span class="d-md-none">
                  <i class="fas fa-save"></i>
              </span>
          </button>
      <?php else: ?>
          <button type="button" wire:click.prevent="Update()"
              class="btn btn-sm btn-outline-primary rounded-pill me-1 d-flex align-items-center float-end"
              wire:loading.attr="disabled" wire:loading.class="disabled" wire:loading.class.remove="btn-outline-primary"
              wire:loading.class.add="btn-primary">
              <!-- Spinner -->
              <span wire:loading wire:target="Update" class="spinner-border spinner-border-sm me-2" role="status"
                  aria-hidden="true"></span>

              <!-- Icon for smaller screens -->
              <span class="d-none d-md-inline">
                  <i class="fas fa-recycle"></i>
                  <span wire:loading.remove wire:target="Update"> Update</span>
              </span>
              <span wire:loading wire:target="Update"> Updating...</span>
              <span class="d-md-none">
                  <i class="fas fa-recycle"></i>
              </span>
          </button>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
  </div>
  </div>
  </div>
  </div>
  <div class="modal-backdrop fade show"></div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/modals/large-modal-footer.blade.php ENDPATH**/ ?>