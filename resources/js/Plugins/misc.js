export const paginate = (datas, chunk_size, current) => {
    let results = [];
    let items = []
    datas.forEach((item,index) => {
        item.no = index+1
        items.push(item)
    })

    items = [].concat(...items)
    while (items.length) {
        results.push(items.splice(0, (chunk_size !== 'all' ? chunk_size : items.length)));
    }
    return {
        current: results[current],
        total: datas.length,
        page_length: results.length
    }
    // return results[page]
}

export const capitalize = (text) => [...text].reduce((s,c,i,a) => s + (i === 0 || a[i - 1] === ' ' ? c.toUpperCase() : c.toLowerCase()), '');

export const defaultImage = (e) => e.target.src = '/img/no-image.jpg';

export const cetak = async (selector, title="Cetak", env) => {
    let host = window.location.host
	let el = document.querySelector(selector)
	let cssUrl = env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
	let html = `<!doctype html>
				<html>
					<head>
						<title>${title}</title>
						<link rel="stylesheet" href="${cssUrl}" />
					</head>
					<body>
						${el.outerHTML}
					</body>

				</html>
	`
	let win = window.open(host+'/print',"_blank","height=600,width=1024")
	await win.document.write(html)
    setTimeout(() => {
        win.print();
        win.close();
    }, 1500);
}

export default { paginate, capitalize, defaultImage, cetak };