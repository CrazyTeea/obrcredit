export default route => new Promise((resolve, reject) => {
    let href = window.location.href;
    href = href.replace('index.php');
    if (Array.isArray(route)) {
        route.forEach(item => {

            if (href.includes(item)) {
                return resolve();
            }
            return null;
        });
    } else if (href.includes(route)) {
        resolve();
    } else {
        reject();
    }
});
