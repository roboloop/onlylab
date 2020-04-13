module.exports = function(dividend, divider, options) {
    if (0 === dividend % divider) {
        console.log(dividend, divider, options);
        let res = options.fn(this);
        console.log(res);
        return options.fn(this);
    }

    return options.inverse(this);
};