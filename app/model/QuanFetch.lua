local quan_id = tostring(KEYS[1])
local uid = tostring(ARGV[1])

-- 应答函数
local function response(errno, msg, data) 
    errno = errno or 0
    msg = msg or ""
    data = data or {}
    return cjson.encode({errno = errno, msg = msg, data = data})
end

-- 判断用户没有抢过该优惠券
local log_key = "LOG_{" .. quan_id .. "}"
-- return log_key
local has_fetched = redis.call("sIsMember", log_key, uid)
if (has_fetched ~= 0) then
    return response(-1, "已经领取过")
end

-- 遍历优惠券所有批次
local quan_key = "QUAN_{" .. quan_id .. "}"
local batch_list = redis.call("hGetAll", quan_key)
local result = false
for batch_idx = 1, #batch_list, 2 do
    repeat
        -- 校验批次状态(是否online)
        local batch_info = cjson.decode(batch_list[batch_idx + 1])
        if (batch_info["online"] ~= true) then
            break
        end

        -- 尝试从券池取出1个券码
        local batch_key = batch_list[batch_idx]
        local coupon = redis.call("zRange", batch_key, 0, 0)
        if (#coupon == 0) then
            break
        end
        coupon = coupon[1]
        redis.call("zRem", batch_key, coupon)

        -- 弹出一个券码, 标记用户已抢
        redis.call("sAdd", log_key, uid)

        -- 将券码放入异步队列
        result = {uid = uid, quanId = quan_id, batchKey = batch_key, coupon = coupon}
        redis.call("rPush", "DB_QUEUE", cjson.encode(result))
    until true

    if result ~= false then
        break
    end
end

if (result == false) then
    return response(-1, "优惠券已抢完")
else
    return response(0, "秒杀成功", result)
end


