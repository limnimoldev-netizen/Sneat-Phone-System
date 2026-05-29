<script>
    // Parent checked → check/uncheck all children
    document.querySelectorAll('.parent-check').forEach(function (parent) {
        parent.addEventListener('change', function () {
            const group = this.dataset.group;
            document.querySelectorAll(`.child-check[data-group="${group}"]`)
                .forEach(child => child.checked = this.checked);
        });
    });

    // Child changed → sync parent state
    document.querySelectorAll('.child-check').forEach(function (child) {
        child.addEventListener('change', function () {
            syncParent(this.dataset.group);
        });
    });

    // Sync parent state helper
    function syncParent(group) {
        const children    = [...document.querySelectorAll(`.child-check[data-group="${group}"]`)];
        const parent      = document.querySelector(`.parent-check[data-group="${group}"]`);
        const allChecked  = children.every(c => c.checked);
        const someChecked = children.some(c => c.checked);
        parent.checked       = allChecked;
        parent.indeterminate = !allChecked && someChecked;
    }

    // On page load — restore parent state (edit page)
    document.querySelectorAll('.parent-check').forEach(function (parent) {
        syncParent(parent.dataset.group);
    });
</script>