export const paginate = (datas, chunk_size, current) => {
    const results = [];
    let items = []
    datas.forEach((item,index) => {
        item.no = index+1
        items.push(item)
    })
    items = [].concat(...items)
    while (items.length) {
        results.push(items.splice(0, chunk_size));
    }

    return {
        current: results[current],
        total: items.length,
        page_length: results.length
    }
    // return results[page]
}

export default { paginate }