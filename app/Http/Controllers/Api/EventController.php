<?php

namespace App\Http\Controllers\Api;

use App\Transformer\EventTransformer;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use App\Models\Event;

/**
 * @group Event information
 *
 * APIs for event information
 */
class EventController extends Controller
{
    /**
     * [GET] Retrieve Event List
     *
     * Retrieve events
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *    "data": [
     *        {
     *            "title": "event 1",
     *            "event_time": "2020-03-22 00:00:00",
     *            "image": "http://localhost:8000/img/default-event.jpg"
     *        },
     *        {
     *            "title": "event 2",
     *            "event_time": "2020-03-22 00:00:00",
     *            "image": "http://localhost:8000/img/default-event.jpg"
     *        }
     *   ],
     *   "meta": {
     *       "limit": 0,
     *       "offset": 0,
     *       "total": 2
     *   }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     */
    public function index(Request $request, EventService $eventService)
    {
        $meta = array(
            'limit' => intval($request->input('limit')),
            'offset' => intval($request->input('offset')),
            'total' => Event::onGoing()->count()
        );
        $response = array(
            'data'    => $eventService->listEvents($request->input('offset'), $request->input('limit')),
            'meta' => $meta
        );

        return response()->json($response);
    }

    /**
     * [GET] Retrieve Event Detail
     *
     * Retrieve event detail
     *
     * @response {
     *  "data": {
     *      "id": 1,
     *      "title": "event 1",
     *      "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non pretium velit, eu sodales nibh. Ut nec tincidunt tellus. Quisque dictum auctor eros, in sagittis risus vulputate vel. Quisque sed neque ligula. Nam id congue quam. Suspendisse lobortis eros eu mi vulputate vestibulum. Nullam augue ligula, fringilla nec arcu non, iaculis porta massa. Mauris vestibulum arcu ac mauris tincidunt mollis at a tellus. Sed blandit ac magna a volutpat. Mauris eget urna lorem.\n\n                Nulla ultrices, enim ut dignissim facilisis, libero sem aliquet augue, id convallis ligula augue in ante. Vestibulum lorem urna, ullamcorper faucibus tristique ut, imperdiet non mauris. In bibendum turpis a arcu vestibulum, ac dapibus quam sagittis. Donec nunc lorem, blandit id leo vitae, tempus blandit nisi. Aenean quis molestie nunc. Praesent eleifend sagittis quam in condimentum. Suspendisse in nisi suscipit, pretium est ut, tristique ante. Mauris quis dolor interdum, convallis velit sed, vehicula tortor. Maecenas eleifend commodo lectus, et luctus diam ullamcorper eget. Curabitur porta nunc et dui molestie, non imperdiet risus dictum.\n\n                Morbi et diam sed turpis interdum maximus. Nulla mattis mi est, sit amet hendrerit leo fermentum et. Praesent eget odio et elit volutpat cursus vitae eget felis. Ut fringilla tellus a pulvinar rhoncus. Vivamus in sapien magna. Curabitur a consectetur tortor, eget tincidunt lacus. Suspendisse a lorem odio. Vivamus rutrum ornare tellus vitae gravida.\n\n                Fusce sed velit vestibulum, tempus magna eget, convallis leo. Vivamus volutpat sed lectus vitae consectetur. Vivamus nibh lorem, ultrices eu orci sed, placerat luctus dolor. Vivamus tellus nunc, commodo in nunc vitae, fringilla tincidunt nisl. Suspendisse potenti. Nunc ut diam in dolor facilisis sollicitudin eget quis ligula. Ut ac iaculis ipsum. Praesent felis tellus, faucibus eget bibendum in, sagittis et felis. Donec eget elit lacus. Quisque eget metus consectetur, posuere libero sit amet, accumsan libero. Aliquam tempor urna sem, a ornare augue mollis ac. Donec eu elementum velit.\n\n                Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris accumsan commodo venenatis. Aenean ultrices vel magna non tristique. Ut odio est, ultricies et ullamcorper et, porttitor vitae augue. Mauris suscipit posuere lorem in congue. Suspendisse laoreet aliquam metus sed fringilla. Vestibulum pharetra rhoncus enim, ut ultrices elit commodo at. Nam maximus sapien non justo ullamcorper, non volutpat erat facilisis. Praesent venenatis enim quam, sit amet eleifend felis sodales at. Vivamus mollis consequat semper. Etiam finibus posuere tellus in imperdiet. Pellentesque elementum pellentesque nulla quis rutrum.",
     *      "location": "Sekolah",
     *      "event_time": "2020-03-22 00:00:00",
     *      "created_by": 1,
     *      "last_updated_by": 1,
     *      "deleted_at": null,
     *      "created_at": "2020-02-21 09:46:39",
     *      "updated_at": "2020-02-21 09:46:39",
     *      "image": "http://localhost:8000/img/default-event.jpg"
     *  }
     *}
     *
     * @response 404 {
     *    "message": "Not Found"
     *}
     */
    public function show(Request $request, $id)
    {
        $response = array(
            'data' => (new EventTransformer())->event(Event::find($id))
        );

        return response()->json($response);
    }
}
