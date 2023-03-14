const searchParams = new URLSearchParams(window.location.search);

const initFilter = (filterName) => {
    const filterByRoad = document.getElementById(filterName)
    filterByRoad.addEventListener('change', (e) => {
        searchParams.set(filterName, e.target.value)
        window.location.search = searchParams.toString()
    })
}

const initSorting = () => {
    const sortingButtons = document.querySelectorAll('.sorting-button')
    sortingButtons.forEach(button => {
        const field = button.dataset['field'] || ''
        const sortByField = button.dataset['sortByField'] || ''
        const isSortAsc = button.dataset['isSortAsc'] || ''
        button.addEventListener('click', () => {
            if (field !== sortByField) {
                searchParams.set('sort_by', field)
                searchParams.set('is_sort_asc', 'true')
            }
            if (field === sortByField && isSortAsc === '1') {
                searchParams.set('is_sort_asc', 'false')
            }
            if (field === sortByField && isSortAsc === '0') {
                searchParams.set('is_sort_asc', 'true')
            }
            window.location.search = searchParams.toString()
        })
    })
}

const initSearch = () => {
    const search = document.getElementById('searchInput')
    const sendFormButton = document.getElementById('sendFormButton')
    sendFormButton.addEventListener('click', () => {
        searchParams.set('search_query', search.value)
        window.location.search = searchParams.toString()
    })
}

window.addEventListener('load', () => {
    initFilter('filter_by_road')
    initFilter('filter_by_position')
    initFilter('filter_by_pavilion')
    initSorting()
    initSearch()
}, { once: true})