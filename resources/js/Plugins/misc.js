export const paginate = (datas, chunk_size, current) => {
    const results = [];
    datas = [].concat(...datas)
    while (datas.length) {
        results.push(datas.splice(0, chunk_size));
    }

    return {
        current: results[current],
        total: datas.length,
        page_length: results.length
    }
    // return results[page]
}

export default { paginate }